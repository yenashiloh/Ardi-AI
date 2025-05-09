# python_api/api_wrapper.py
from flask import Flask, request, jsonify
from flask_cors import CORS
import sys
import os
import time  # Add this import
import traceback
import uuid  # For generating query IDs
import threading  # For background processing
from collections import defaultdict  # For query queue

# Add parent directory to Python path to import your modules
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

# Import your chatbot and other modules
from src.chatbot import CompanyKnowledgeBot
from src.access_control import AccessControl

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Global storage for query queue
query_queue = {}

@app.route('/health', methods=['GET'])
def health_check():
    """Endpoint to check if API is running"""
    return jsonify({"status": "healthy"})

@app.route('/test', methods=['GET'])
def test():
    """Simple test endpoint"""
    return jsonify({
        "status": "ok",
        "message": "API is working correctly"
    })

@app.route('/query', methods=['POST'])
def handle_query():
    """Process knowledge base queries"""
    print("Starting query processing...")
    start_time = time.time()

    try:
        # Get JSON data from request
        data = request.json
        if not data:
            return jsonify({
                "success": False,
                "error": "No JSON data provided"
            }), 400

        # Log request data
        print(f"Request data: {data}")
        print(f"Elapsed time (request parsing): {time.time() - start_time:.2f}s")
        query_start = time.time()

        # Extract query and user data
        query = data.get('query', '')
        if not query:
            return jsonify({
                "success": False,
                "error": "No query provided"
            }), 400

        # Map Laravel role to clearance level
        role = data.get('role', 'standard')
        clearance_level = "confidential" if role == "Admin" else "standard"

        # Create user context
        user_context = {
            "user_id": data.get('user_id', 'anonymous'),
            "name": f"{data.get('first_name', '')} {data.get('last_name', '')}",
            "department": data.get('department', 'General'),
            "clearance_level": clearance_level
        }

        print(f"User context: {user_context}")
        print(f"Query: '{query}'")
        print(f"Elapsed time (setup): {time.time() - query_start:.2f}s")

        chatbot_start = time.time()
        print("Initializing chatbot...")

        # Initialize chatbot with user context
        chatbot = CompanyKnowledgeBot(user_context)

        print(f"Elapsed time (chatbot init): {time.time() - chatbot_start:.2f}s")
        answer_start = time.time()

        print("Processing query with chatbot...")
        # Get response
        response = chatbot.answer(query)

        print(f"Elapsed time (answer generation): {time.time() - answer_start:.2f}s")
        print(f"Total elapsed time: {time.time() - start_time:.2f}s")
        print(f"Response length: {len(response)} characters")

        # Return response
        return jsonify({
            "success": True,
            "response": response,
            "clearance_level": clearance_level
        })

    except Exception as e:
        print(f"Error processing query: {str(e)}")
        print(traceback.format_exc())
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

# Asynchronous API endpoints for handling long-running queries
@app.route('/submit_query', methods=['POST'])
def submit_query():
    """Submit a query for processing in the background"""
    try:
        data = request.json
        query_id = str(uuid.uuid4())

        # Store the query details for background processing
        query_queue[query_id] = {
            'data': data,
            'status': 'pending',
            'submitted_at': time.time()
        }

        # Start a background thread to process the query
        threading.Thread(target=process_query_in_background, args=(query_id,)).start()

        return jsonify({
            'success': True,
            'message': 'Query submitted for processing',
            'query_id': query_id
        })
    except Exception as e:
        print(f"Error submitting query: {str(e)}")
        print(traceback.format_exc())
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/query_status/<query_id>', methods=['GET'])
def query_status(query_id):
    """Check the status of a query"""
    if query_id not in query_queue:
        return jsonify({
            'success': False,
            'error': 'Query not found'
        }), 404

    query_data = query_queue[query_id]

    # If the query is complete, return the response
    if query_data['status'] == 'complete':
        response = query_data['response']
        # Remove from queue to free up memory (optional)
        # if query_id in query_queue:
        #     del query_queue[query_id]
        return jsonify({
            'success': True,
            'status': 'complete',
            'response': response
        })

    # If the query failed, return the error
    if query_data['status'] == 'failed':
        error = query_data['error']
        # Keep the error for a limited time then remove
        return jsonify({
            'success': False,
            'status': 'failed',
            'error': error
        })

    # If the query is still pending, return the status
    elapsed_time = time.time() - query_data['submitted_at']
    return jsonify({
        'success': True,
        'status': 'pending',
        'message': f'Query is still processing (elapsed time: {elapsed_time:.1f}s)'
    })

def process_query_in_background(query_id):
    """Process a query in the background"""
    print(f"Starting background processing for query {query_id}")
    start_time = time.time()

    query_data = query_queue[query_id]
    data = query_data['data']

    try:
        query = data.get('query', '')
        if not query:
            raise ValueError("No query provided")

        print(f"Processing query: '{query}'")

        role = data.get('role', 'standard')
        clearance_level = "confidential" if role == "Admin" else "standard"

        user_context = {
            "user_id": data.get('user_id', 'anonymous'),
            "name": f"{data.get('first_name', '')} {data.get('last_name', '')}",
            "department": data.get('department', 'General'),
            "clearance_level": clearance_level
        }

        print(f"User context: {user_context}")

        chatbot_start = time.time()
        print("Initializing chatbot...")

        # Initialize chatbot and get response
        chatbot = CompanyKnowledgeBot(user_context)

        print(f"Chatbot initialized in {time.time() - chatbot_start:.2f}s")
        answer_start = time.time()

        print("Getting answer...")
        response = chatbot.answer(query)

        print(f"Answer generated in {time.time() - answer_start:.2f}s")
        print(f"Total processing time: {time.time() - start_time:.2f}s")

        # Store the response
        query_queue[query_id]['status'] = 'complete'
        query_queue[query_id]['response'] = response
        query_queue[query_id]['completed_at'] = time.time()

        print(f"Background processing completed for query {query_id}")

    except Exception as e:
        # Store the error
        print(f"Background processing error for query {query_id}: {str(e)}")
        print(traceback.format_exc())
        query_queue[query_id]['status'] = 'failed'
        query_queue[query_id]['error'] = str(e)

if __name__ == '__main__':
    # Get port from command line argument or use default
    port = int(sys.argv[1]) if len(sys.argv) > 1 else 5000
    print(f"Starting API server on port {port}")
    app.run(host='0.0.0.0', port=port, debug=False)

<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    //Show Documents Page
    public function showDocumentsPage()
    {
        $user = Auth::user();
        $documents = Document::all(); // Fetch all documents

        return view('admin.documents.documents', [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $user->role,
            'documents' => $documents
        ]);
    }

    //Store Document
    public function storeDocument(Request $request)
    {
        // Validate the request with a custom error message for file type
        $request->validate([
            'document_type' => 'required|string',
            'notes' => 'nullable|string',
            'uploadDocument' => 'required|file|mimes:pdf',
        ], [
            'uploadDocument.mimes' => 'Only PDF files are allowed.' 
        ]);
    
        // Handle the file upload
        if ($request->hasFile('uploadDocument')) {
            // Get the original file name
            $originalFileName = $request->file('uploadDocument')->getClientOriginalName();
    
            // Store the file in the 'uploads' directory within the 'public' disk
            $filePath = $request->file('uploadDocument')->storeAs('uploads', $originalFileName, 'public');
    
            // Save document details to MongoDB, using the original file name
            $document = new Document([
                'document_type' => $request->input('document_type'),
                'notes' => $request->input('notes'),
                'original_file_name' => $originalFileName,
                'file_path' => $filePath,
            ]);
            $document->save();

            return redirect()->route('admin.documents.documents')->with('success', 'Document uploaded successfully!');
        }
    
        return redirect()->route('admin.documents.documents')->with('error', 'File upload failed');
    }    
    
    //Delete Document
    public function destroyDocument($id)
    {
        try {
            $document = Document::find($id);
            
            // Delete the file from storage if it exists
            if ($document && Storage::exists('public/' . $document->file_path)) {
                Storage::delete('public/' . $document->file_path);
            }
            
            // Delete the document record
            $document->delete();
            
            return redirect()->route('admin.documents.documents')
                ->with('success', 'Document Deleted Successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.documents.documents')
                ->with('error', 'Failed to delete document: ' . $e->getMessage());
        }
    }
    
    //Update Document
    public function updateDocument(Request $request, $id)
    {
        try {
            $document = Document::findOrFail($id);
            
            // Validate request
            $request->validate([
                'document_type' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'file' => 'nullable|file|max:10240', // Optional file, max 10MB
            ]);
            
            // Update basic info
            $document->document_type = $request->document_type;
            $document->notes = $request->notes;
            
            // Handle file upload if a new file is provided
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                // Delete old file
                if (Storage::exists('public/' . $document->file_path)) {
                    Storage::delete('public/' . $document->file_path);
                }
                
                // Store new file
                $file = $request->file('file');
                $path = $file->store('documents', 'public');
                
                // Update document with new file info
                $document->original_file_name = $file->getClientOriginalName();
                $document->file_path = $path;
                $document->file_size = $file->getSize();
                $document->file_type = $file->getMimeType();
            }
            
            // Save changes
            $document->save();
            
            return redirect()->route('admin.documents.documents')
                ->with('success', 'Document updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.documents.documents')
                ->with('error', 'Failed to update document: ' . $e->getMessage());
        }
    }
}
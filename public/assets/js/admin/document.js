/**
 * Upload Documents
 */
document.getElementById('uploadDocument').addEventListener('change', function () {
    const file = this.files[0];
    const errorMessage = document.getElementById('fileErrorMessage');
    const fileType = file ? file.type : '';

    // Check if the selected file is a PDF
    if (file && fileType !== 'application/pdf') {
        errorMessage.textContent = 'Only PDF files are allowed.';
        errorMessage.classList.add('text-danger');
        document.getElementById('uploadDocument').value = ''; // Clear the file input
        errorMessage.style.fontSize = '0.75rem'; // Make the font smaller
    } else {
        errorMessage.textContent = ''; // Clear error message if it's a PDF
        errorMessage.classList.remove('text-danger');
        errorMessage.style.fontSize = ''; // Reset the font size
    }
});

/**
 * Delete Documents
 */
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const documentId = button.getAttribute('data-id');
            const deleteForm = document.getElementById('delete-form');
            deleteForm.action = '/documents/' + documentId;
        });
    }
});

/**
 * Edit Documents
 */
const editModal = document.getElementById('documentEditModal');
if (editModal) {
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const documentId = button.getAttribute('data-id');
        const documentType = button.getAttribute('data-type');
        const notes = button.getAttribute('data-notes');
        const filePath = button.getAttribute('data-file-path');
        const fileName = button.getAttribute('data-file-name');
        
        // Set values in the form
        document.getElementById('edit-document-id').value = documentId;
        document.getElementById('edit-document-type').value = documentType;
        document.getElementById('edit-notes').value = notes;
        
        // Set current file link
        const fileLink = document.getElementById('current-file-link');
        fileLink.href = '/storage/' + filePath;
        fileLink.textContent = fileName;
        
        // Set form action with the correct URL
        const form = document.getElementById('edit-document-form');
        form.action = `/documents/${documentId}`;
    });
}

// Add form submit handler to ensure method spoofing is working
document.getElementById('edit-document-form').addEventListener('submit', function(e) {
    // The form already has @method('PUT') in the Blade template
    // This is just additional insurance
    const methodInput = document.querySelector('input[name="_method"]');
    if (!methodInput) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = 'PUT';
        this.appendChild(input);
    }
});
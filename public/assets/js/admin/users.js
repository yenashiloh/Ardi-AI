document.addEventListener("DOMContentLoaded", function () {
    const addUserForm = document.getElementById("addUserForm");
    
    if (!addUserForm) {
        console.error("Add user form not found");
        return;
    }
    
    const submitButton = addUserForm.querySelector("button[type='submit']");
    
    // Create loader spinner
    const loader = document.createElement("span");
    loader.className = "spinner-border spinner-border-sm text-light ms-2";
    loader.setAttribute("role", "status");
    loader.style.display = "none";
    submitButton.appendChild(loader);

    addUserForm.addEventListener("submit", function (e) {
        e.preventDefault();

        // Get CSRF token
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
        if (!csrfToken) {
            csrfToken = document.querySelector('input[name="_token"]')?.value;
        }
        
        if (!csrfToken) {
            displayErrors({"csrf": ["CSRF token not found. Please refresh the page."]});
            return;
        }

        // Clear previous messages
        document.getElementById("errorMessages").innerHTML = "";
        document.getElementById("successMessage").innerHTML = "";
        
        // Show loading state
        submitButton.disabled = true;
        loader.style.display = "inline-block";

        // Create form data
        let formData = new FormData(addUserForm);
        
        // Convert FormData to JSON
        let formDataObj = {};
        formData.forEach((value, key) => {
            formDataObj[key] = value;
        });

        fetch("/users/store", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(formDataObj)
        })
        .then(response => response.json())
        .then(data => {
            // Reset loading state
            submitButton.disabled = false;
            loader.style.display = "none";

            if (data.status === 'error' && data.errors) {
                displayErrors(data.errors);
            } else if (data.status === 'success') {
                // Hide modal immediately
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Display success message
                displaySuccessMessage(data.message);
                addUserForm.reset();
                
                // Reload page after a short delay (to allow user to see the success message)
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                displayErrors({"general": ["An unexpected error occurred. Please try again."]});
            }
        })
        .catch(error => {
            console.error("Error:", error);
            submitButton.disabled = false;
            loader.style.display = "none";
            displayErrors({"connection": ["Connection error. Please check your internet connection and try again."]});
        });
    });

    function displayErrors(errors) {
        let errorContainer = document.getElementById("errorMessages");
        errorContainer.innerHTML = "";
        
        // Create a single alert box for all errors
        let alert = document.createElement("div");
        alert.className = "alert alert-danger alert-dismissible fade show";
        
        // Collect all error messages
        let errorMessages = [];
        for (const key in errors) {
            if (errors.hasOwnProperty(key)) {
                errorMessages.push(errors[key][0]);
            }
        }
        
        if (errorMessages.length === 0) {
            return; // No errors to display
        } else if (errorMessages.length === 1) {
            // For a single error, just show the message directly
            alert.innerHTML = `
                ${errorMessages[0]}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
        } else {
            // For multiple errors, use a bullet list
            alert.innerHTML = `
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Create bullet points for all error messages
            let errorList = document.createElement("ul");
            errorList.className = "mb-0 ps-3"; 
            
            errorMessages.forEach(message => {
                let errorItem = document.createElement("li");
                errorItem.textContent = message;
                errorList.appendChild(errorItem);
            });
            
            alert.appendChild(errorList);
        }
        
        errorContainer.appendChild(alert);
    }

    function displaySuccessMessage(message) {
        let successContainer = document.getElementById("successMessage");
        successContainer.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }
});

/**
 * Import Users Details
 */
$(document).ready(function() {
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData();
        var fileInput = $('#importFile')[0].files[0];
        formData.append('importFile', fileInput);
        
        $.ajax({
            url: '/users/import',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                // Show loading indicator
                $('#importForm button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importing...');
                $('#importForm button[type="submit"]').prop('disabled', true);
                
                // Clear previous messages
                $('#successMessage').html('');
            },
            success: function(response) {
                // Hide modal
                $('#importModal').modal('hide');
                
                // Create success message with error details if any
                var messageHtml = '<div class="alert alert-' + (response.errors && response.errors.length > 0 ? 'warning' : 'success') + 
                                  ' alert-dismissible fade show" role="alert">' +
                                  '<p>' + response.message + '</p>';
                
                // Add error details if available
                if (response.errors && response.errors.length > 0) {
                    messageHtml += '<ul class="mt-2 mb-0">';
                    response.errors.forEach(function(error) {
                        messageHtml += '<li>' + error + '</li>';
                    });
                    messageHtml += '</ul>';
                }
                
                // Add debug info if available
                if (response.debug_info) {
                    messageHtml += '<hr><p class="mb-0 small">Debug Info: Total rows: ' + 
                                   response.debug_info.total_rows + '</p>';
                }
                
                messageHtml += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                
                // Show message in the successMessage container
                $('#successMessage').html(messageHtml);
                
                // Reload user list if needed
                if (typeof loadUsers === 'function') {
                    loadUsers();
                }
                
                // Reset form
                $('#importForm')[0].reset();
            },
            error: function(xhr) {
                var errorMessage = 'An error occurred during import.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                // Show error message in the successMessage container
                $('#successMessage').html(
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<p>' + errorMessage + '</p>' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>'
                );
                
                // If there are validation errors
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    for (var key in errors) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key + 'Error').text(errors[key][0]);
                    }
                }
            },
            complete: function() {
                // Reset button state
                $('#importForm button[type="submit"]').html('Submit');
                $('#importForm button[type="submit"]').prop('disabled', false);
            }
        });
    });
});

/**
 * Archive Confirm Message 
 */

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.archive-btn').forEach(button => {
        button.addEventListener('click', function() {
            let userId = this.getAttribute('data-id');

            Swal.fire({
                title: "Are you sure?",
                text: "This user will be archived",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, archive it"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/users/${userId}/archive`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire(
                            "Archived!",
                            data.message,
                            "success"
                        ).then(() => location.reload()); 
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire("Error!", "Something went wrong.", "error");
                    });
                }
            });
        });
    });
});

/**
 * Disable Confirm Message
 */
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.disable-btn').forEach(button => {
        button.addEventListener('click', function() {
            let userId = this.getAttribute('data-id');

            Swal.fire({
                title: "Are you sure?",
                text: "You are about to disable this user",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, disable it"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/users/${userId}/disable`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire("Disabled!", data.message, "success").then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Something went wrong!", "error");
                        console.error('Error:', error);
                    });
                }
            });
        });
    });
});

/**
 * Activate Confirm Message
 */
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.activate-btn').forEach(button => {
        button.addEventListener('click', function() {
            let userId = this.getAttribute('data-id');

            Swal.fire({
                title: "Are you sure?",
                text: "This user will be activated.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, activate"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/users/${userId}/activate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire("Activated!", data.message, "success").then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Something went wrong!", "error");
                        console.error('Error:', error);
                    });
                }
            });
        });
    });
});

/**
 * User Details Modal
 */

document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-user-btn');
    const modalBody = document.getElementById('userDetailsModalBody');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const idNumber = this.getAttribute('data-id-number');
            
            // Show loading state
            modalBody.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            // Fetch user details
            fetch(`/admin/users/details/${idNumber}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('User details not found');
                    }
                    return response.json();
                })
                .then(data => {
                    // Create a detailed view of user information
                    let detailsHtml = '<div class="user-details-grid">';
                    
                    // First Row: Date Created, ID Number
                    detailsHtml += `
                        <div class="user-details-row">
                            <div class="user-detail-item">
                                <strong class="user-detail-label">Date Created</strong>
                                <span class="user-detail-value">${data.user['Date Created']}</span>
                            </div>
                            <div class="user-detail-item">
                                <strong class="user-detail-label">ID Number</strong>
                                <span class="user-detail-value">${data.user['ID Number']}</span>
                            </div>
                        </div>
                    `;
                    
                    // Second Row: First Name, Last Name
                    detailsHtml += `
                        <div class="user-details-row">
                            <div class="user-detail-item">
                                <strong class="user-detail-label">First Name</strong>
                                <span class="user-detail-value">${data.user['First Name']}</span>
                            </div>
                            <div class="user-detail-item">
                                <strong class="user-detail-label">Last Name</strong>
                                <span class="user-detail-value">${data.user['Last Name']}</span>
                            </div>
                        </div>
                    `;
                    
                    // Third Row: Email (full width)
                    detailsHtml += `
                        <div class="user-details-row">
                            <div class="user-detail-item full-width">
                                <strong class="user-detail-label">Email</strong>
                                <span class="user-detail-value">${data.user['Email']}</span>
                            </div>
                        </div>
                    `;
                    
                    // Fourth Row: Role, Status
                    detailsHtml += `
                        <div class="user-details-row">
                            <div class="user-detail-item">
                                <strong class="user-detail-label">Role</strong>
                                <span class="user-detail-value">${data.user['Role']}</span>
                            </div>
                            <div class="user-detail-item">
                                <strong class="user-detail-label">Status</strong>
                                <span class="user-detail-value">${data.user['Status']}</span>
                            </div>
                        </div>
                    `;
                    
                    detailsHtml += '</div>';
                    
                    modalBody.innerHTML = detailsHtml;
                })
                .catch(error => {
                    modalBody.innerHTML = `
                        <div class="alert alert-danger">
                            ${error.message}
                        </div>
                    `;
                });
        });
    });
});

/**
 * Users Update 
 */
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.table-action-btn[title="Edit"]');
    const editForm = document.getElementById('userEditForm');
    const modalBody = document.querySelector('#userEditModal .modal-body');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Show loading state
            modalBody.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            // Find the closest table row
            const row = this.closest('tr');
            
            // Try to find the view button within the same row to get the id-number
            const viewButton = row.querySelector('.view-user-btn');
            
            // If view button exists, get the id-number
            const idNumber = viewButton ? viewButton.getAttribute('data-id-number') : null;
            
            if (!idNumber) {
                modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        Could not find user ID
                    </div>
                `;
                return;
            }

            // Fetch user details for editing
            fetch(`/admin/users/edit/${idNumber}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('User details not found');
                    }
                    return response.json();
                })
                .then(data => {
                    // Restore the original form
                    modalBody.innerHTML = `
                        <form id="userEditForm">
                            <div id="edit-error-container" class="mb-3"></div>
                            
                            <input type="hidden" id="edit-id-number" name="id_number" value="${idNumber}">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="edit-first-name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="edit-first-name" name="first_name" required value="${data.user.first_name}">
                                </div>
                                <div class="col-md-6">
                                    <label for="edit-last-name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="edit-last-name" name="last_name" required value="${data.user.last_name}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit-email" name="email" required value="${data.user.email}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit-role" class="form-label">Role</label>
                                <select class="form-select" id="edit-role" name="role" required>
                                    <option value="" disabled>Select Role</option>
                                    <option value="Admin" ${data.user.role === 'Admin' ? 'selected' : ''}>Admin</option>
                                    <option value="Collaborators" ${data.user.role === 'Collaborators' ? 'selected' : ''}>Collaborators</option>
                                    <option value="Team Leader" ${data.user.role === 'Team Leader' ? 'selected' : ''}>Team Leader</option>
                                    <option value="Non-Billable" ${data.user.role === 'Non-Billable' ? 'selected' : ''}>Non-Billable</option>
                                    <option value="Billable" ${data.user.role === 'Billable' ? 'selected' : ''}>Billable</option>
                                </select>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    `;

                    // Reattach form submission event listener
                    document.getElementById('userEditForm').addEventListener('submit', handleFormSubmit);
                })
                .catch(error => {
                    modalBody.innerHTML = `
                        <div class="alert alert-danger">
                            ${error.message}
                        </div>
                    `;
                });
        });
    });

    function handleFormSubmit(e) {
        e.preventDefault();
        
        const submitButton = e.target.querySelector('button[type="submit"]');
        const errorContainer = document.getElementById('edit-error-container');
        
        // Clear previous errors
        errorContainer.innerHTML = '';
        
        // Disable button and add loader
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Saving...
        `;
        
        const idNumber = document.getElementById('edit-id-number').value;
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        // Send update request
        fetch(`/admin/users/update/${idNumber}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            // Check if the response is ok
            if (!response.ok) {
                // Try to parse error response
                return response.json().then(errorData => {
                    // Custom error handling for specific scenarios
                    if (errorData.errors) {
                        let errorMessages = [];
                        if (errorData.errors.email) {
                            errorMessages.push('The email is already exist ');
                        }
                        if (errorData.errors.id_number) {
                            errorMessages.push('Invalid ID number');
                        }
                        throw new Error(errorMessages.join('. '));
                    }
                    throw new Error(errorData.error || 'An error occurred');
                });
            }
            return response.json();
        })
        .then(result => {
            // Close the modal programmatically
            const modalElement = document.getElementById('userEditModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide();

            // Create and insert success alert in the specified container
            const successMessageContainer = document.getElementById('successMessage');
            successMessageContainer.innerHTML = `
                <div class="alert alert-success" role="alert">
                    ${result.message}
                </div>
            `;
            
            // Remove the alert after 10 seconds
            setTimeout(() => {
                successMessageContainer.innerHTML = '';
            }, 10000);
        })
        .catch(error => {
            // Show error in the error container at the top of the form
            errorContainer.innerHTML = `
                <div class="alert alert-danger">
                    ${error.message}
                </div>
            `;
            
            // Re-enable button and restore original text
            submitButton.disabled = false;
            submitButton.innerHTML = 'Save Changes';
        });
    }
});
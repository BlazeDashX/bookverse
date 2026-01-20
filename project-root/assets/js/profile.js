document.addEventListener('DOMContentLoaded', function() {
    const updateForm = document.getElementById('updateProfileForm');
    const deleteForm = document.getElementById('deleteAccountForm');

    // Error Handling Helpers
    function showError(inputId, msgId, message) {
        const input = document.getElementById(inputId);
        const errorSpan = document.getElementById(msgId);
        if (input && errorSpan) {
            input.classList.add('input-error');
            errorSpan.innerText = message;
            errorSpan.style.display = 'block';
        }
    }

    function clearError(inputId, msgId) {
        const input = document.getElementById(inputId);
        const errorSpan = document.getElementById(msgId);
        if (input && errorSpan) {
            input.classList.remove('input-error');
            errorSpan.style.display = 'none';
        }
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Toggle Password Visibility
    window.togglePasswordVisibility = function() {
        document.querySelectorAll('.password-field').forEach(field => {
            field.type = (field.type === "password") ? "text" : "password";
        });
    };

    // Update Profile Validation
    if (updateForm) {
        // Live clear errors
        ['email', 'newPass', 'confirmPass', 'currentPass'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', () => clearError(id, 'err-' + id));
        });

        updateForm.addEventListener('submit', function(e) {
            let isValid = true;
            const email = document.getElementById('email').value.trim();
            const currentPass = document.getElementById('currentPass').value;
            const newPass = document.getElementById('newPass').value;
            const confirmPass = document.getElementById('confirmPass').value;

            // Validate Email
            if (email === "") {
                showError('email', 'err-email', "Email is required.");
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('email', 'err-email', "Invalid email format.");
                isValid = false;
            }

            // Validate Current Password
            if (currentPass === "") {
                showError('currentPass', 'err-currentPass', "Current password required.");
                isValid = false;
            }

            // Validate New Password (Optional)
            if (newPass !== "") {
                if (newPass.length < 6) {
                    showError('newPass', 'err-newPass', "Min 6 characters.");
                    isValid = false;
                }
                if (confirmPass === "") {
                    showError('confirmPass', 'err-confirmPass', "Confirm password.");
                    isValid = false;
                } else if (newPass !== confirmPass) {
                    showError('confirmPass', 'err-confirmPass', "Passwords do not match.");
                    isValid = false;
                }
            }

            if (!isValid) e.preventDefault();
        });
    }

    // Delete Account Validation
    if (deleteForm) {
        const deleteInput = document.getElementById('confirmDeletePass');
        if (deleteInput) {
            deleteInput.addEventListener('input', () => clearError('confirmDeletePass', 'err-delete'));
        }

        deleteForm.addEventListener('submit', function(e) {
            if (deleteInput.value.trim() === "") {
                e.preventDefault();
                showError('confirmDeletePass', 'err-delete', "Password required.");
            }
        });
    }
});
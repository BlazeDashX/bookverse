document.addEventListener('DOMContentLoaded', function() {
    const addForm = document.getElementById('addForm');

    // --- 1. IMAGE PREVIEW LOGIC ---
    // Place this here so it triggers on 'change'
    const coverInput = document.getElementById('coverInput');
    const bookPreview = document.getElementById('bookPreview');

    if (coverInput) {
        coverInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    bookPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // --- 2. INLINE VALIDATION LOGIC ---
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Helper to clear previous errors
            const clearErrors = () => {
                document.querySelectorAll('.err-msg').forEach(el => el.innerText = "");
            };
            clearErrors();

            // Validate Title
            const title = document.getElementById('title').value.trim();
            if (title === "") {
                document.getElementById('errTitle').innerText = "Title is required";
                isValid = false;
            }

            // Validate Author
            const author = document.getElementById('author').value.trim();
            if (author === "") {
                document.getElementById('errAuthor').innerText = "Author is required";
                isValid = false;
            }

            // Validate Image Size (if selected)
            if (coverInput.files.length > 0) {
                const size = coverInput.files[0].size / 1024 / 1024;
                if (size > 2) {
                    document.getElementById('errImage').innerText = "Image must be under 2MB";
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault(); // Stop submission if errors exist
            }
        });
    }
});
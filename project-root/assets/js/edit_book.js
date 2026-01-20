document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editForm');
    const coverInput = document.getElementById('coverInput');
    const bookPreview = document.getElementById('bookPreview');

    // --- 1. REAL-TIME IMAGE PREVIEW ---
    if (coverInput) {
        coverInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    bookPreview.src = e.target.result;
                    // Reset image error catch if a new valid image is picked
                    document.getElementById('errImage').innerText = "";
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // --- 2. INLINE ERROR CATCHING ---
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Helper to clear all previous error catches
            const clearErrors = () => {
                document.querySelectorAll('.err-msg').forEach(el => el.innerText = "");
                document.querySelectorAll('input, textarea').forEach(el => el.classList.remove('invalid'));
            };
            clearErrors();

            // Catch Title Error
            const title = document.getElementById('title');
            if (title.value.trim() === "") {
                document.getElementById('errTitle').innerText = "Title cannot be empty.";
                title.classList.add('invalid');
                isValid = false;
            }

            // Catch Author Error
            const author = document.getElementById('author');
            if (author.value.trim() === "") {
                document.getElementById('errAuthor').innerText = "Author name is required.";
                author.classList.add('invalid');
                isValid = false;
            }

            // Catch Numeric Errors (Stock/Price)
            const stock = document.getElementById('stock_qty');
            if (stock.value < 0) {
                document.getElementById('errStock').innerText = "Stock cannot be negative.";
                stock.classList.add('invalid');
                isValid = false;
            }

            const sellPrice = document.getElementById('sell_price');
            if (sellPrice.value < 0) {
                document.getElementById('errSell').innerText = "Price cannot be negative.";
                sellPrice.classList.add('invalid');
                isValid = false;
            }

            // Catch Image Size Error
            if (coverInput.files.length > 0) {
                const size = coverInput.files[0].size / 1024 / 1024; // MB
                if (size > 5) {
                    document.getElementById('errImage').innerText = "Max size is 5MB.";
                    isValid = false;
                }
            }

            // If any error was caught, stop form submission
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});


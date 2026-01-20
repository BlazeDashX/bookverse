document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editForm');
    const coverInput = document.getElementById('coverInput');
    const bookPreview = document.getElementById('bookPreview');

    // Image preview
    if (coverInput && bookPreview) {
        coverInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    bookPreview.src = e.target.result;
                    document.getElementById('errImage').innerText = "";
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form validation
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Clear errors
            document.querySelectorAll('.err-msg').forEach(el => el.innerText = "");
            document.querySelectorAll('input, textarea').forEach(el => el.classList.remove('invalid'));

            // Validate Title
            const title = document.getElementById('title');
            if (title.value.trim() === "") {
                document.getElementById('errTitle').innerText = "Title cannot be empty.";
                title.classList.add('invalid');
                isValid = false;
            }

            // Validate Author
            const author = document.getElementById('author');
            if (author.value.trim() === "") {
                document.getElementById('errAuthor').innerText = "Author name is required.";
                author.classList.add('invalid');
                isValid = false;
            }

            // Validate Stock
            const stock = document.getElementById('stock_qty');
            if (stock.value < 0) {
                document.getElementById('errStock').innerText = "Stock cannot be negative.";
                stock.classList.add('invalid');
                isValid = false;
            }

            // Validate Price
            const sellPrice = document.getElementById('sell_price');
            if (sellPrice.value < 0) {
                document.getElementById('errSell').innerText = "Price cannot be negative.";
                sellPrice.classList.add('invalid');
                isValid = false;
            }

            // Validate Image Size (Max 5MB)
            if (coverInput.files.length > 0) {
                const size = coverInput.files[0].size / 1024 / 1024;
                if (size > 5) {
                    document.getElementById('errImage').innerText = "Max size is 5MB.";
                    isValid = false;
                }
            }

            // Prevent submission on error
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
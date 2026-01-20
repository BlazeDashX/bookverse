document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('shelfSearch');
    const listContainer = document.getElementById('bookList');

    // Attach Search Listener
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            searchBooks(this.value);
        });
    }

    // AJAX Search Logic
    function searchBooks(term) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../../controller/user_controller.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        renderBookList(response.data, term);
                    }
                } catch (e) {
                    console.error("JSON Parse Error");
                }
            }
        };

        xhr.send(JSON.stringify({ search_term: term }));
    }

    // Render HTML Table
    function renderBookList(data, term) {
        let html = "";

        if (data.length === 0) {
            html = `<tr><td colspan="5" class="empty-state">No books found matching "${term}"</td></tr>`;
        } else {
            data.forEach(book => {
                const badgeClass = (book.payment_type === 'buy') ? 'sell' : 'rent';
                const dateStr = new Date(book.payment_date).toLocaleDateString("en-US", {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                html += `
                <tr>
                    <td>
                        <div class="img-container">
                            <img src="../../assets/images/uploaded/${book.image_filename}" 
                                 alt="Cover" 
                                 onerror="this.src='../../assets/images/default.png'">
                        </div>
                    </td>
                    <td>
                        <div class="book-info">
                            <span class="book-title">${book.title}</span>
                            <span class="book-author">by ${book.author}</span>
                        </div>
                    </td>
                    <td>
                        <span class="status-pill ${badgeClass}">
                            ${book.payment_type.toUpperCase()}
                        </span>
                    </td>
                    <td>${dateStr}</td>
                    <td>
                        <a href="#" class="btn-add-main" style="padding: 8px 20px; font-size: 0.85rem; box-shadow: none;">
                            <i class="fas fa-book-reader"></i> Read
                        </a>
                    </td>
                </tr>`;
            });
        }
        listContainer.innerHTML = html;
    }
});
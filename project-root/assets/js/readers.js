// Confirm User Delete
function confirmUserDelete(id, name) {
    const modal = document.getElementById('deleteModal');
    const nameSpan = document.getElementById('readerNameDisplay');
    const confirmBtn = document.getElementById('confirmDeleteBtn');

    if (modal && nameSpan && confirmBtn) {
        nameSpan.innerText = name;
        confirmBtn.href = `../../controller/deleteReaderController.php?id=${id}`;
        modal.classList.add('active');
    }
}

// Close Modal
function closeModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) modal.classList.remove('active');
}

// Auto-load Notification
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');

    if (msg) {
        const modal = document.getElementById('deleteModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalIcon = document.getElementById('modalIcon');
        const nameSpan = document.getElementById('readerNameDisplay');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = modal.querySelector('.btn-cancel');

        // Style as Success
        if (modalTitle) modalTitle.innerText = "Success!";
        if (modalIcon) {
            modalIcon.className = "fa fa-check-circle";
            modalIcon.style.color = "#4BB543";
        }

        if (nameSpan) nameSpan.innerText = msg.replace(/\+/g, ' ');
        if (confirmBtn) confirmBtn.style.display = "none";
        if (cancelBtn) cancelBtn.innerText = "Close";

        modal.classList.add('active');
        
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
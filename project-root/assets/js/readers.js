
// Function to open the delete confirmation modal
function confirmUserDelete(id, name) {
    const modal = document.getElementById('deleteModal');
    const nameSpan = document.getElementById('readerNameDisplay');
    const confirmBtn = document.getElementById('confirmDeleteBtn');

    if(modal && nameSpan && confirmBtn) {
        nameSpan.innerText = name;
        confirmBtn.href = `../../controller/deleteReaderController.php?id=${id}`;
        modal.classList.add('active'); // Shows the modal
    }
}

function closeModal() {
    const modal = document.getElementById('deleteModal');
    if(modal) modal.classList.remove('active');
}

// Handle Successful Action Popup
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');

    if (msg) {
        const modal = document.getElementById('deleteModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalIcon = document.getElementById('modalIcon');
        
        // Re-styling the modal to look like a Success Toast
        if(modalTitle) modalTitle.innerText = "Success!";
        if(modalIcon) {
            modalIcon.className = "fa fa-check-circle";
            modalIcon.style.color = "#4BB543";
        }
        
        document.getElementById('readerNameDisplay').innerText = msg.replace(/\+/g, ' ');
        document.getElementById('confirmDeleteBtn').style.display = "none";
        modal.querySelector('.btn-cancel').innerText = "Close";
        
        modal.classList.add('active');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
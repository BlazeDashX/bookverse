/**
 * BOOKVERSE UNIFIED POPUP SYSTEM
 */

// 1. DELETE CONFIRMATION LOGIC
function confirmDelete(id, title) {
    const modal = document.getElementById('deleteModal');
    const titleSpan = document.getElementById('bookTitleDisplay');
    const confirmBtn = document.getElementById('confirmDeleteBtn');

    if(modal && titleSpan && confirmBtn) {
        titleSpan.innerText = `"${title}"`;
        confirmBtn.href = `../../controller/deleteBookController.php?id=${id}`;
        modal.classList.add('active');
    }
}

// 2. GENERAL NOTIFICATION POPUP (For Add/Update/Delete results)
function showNotificationPopup(message) {
    const modal = document.getElementById('deleteModal');
    const titleSpan = document.getElementById('bookTitleDisplay');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const modalHeader = modal.querySelector('h3');
    const warningIcon = modal.querySelector('.fa-exclamation-triangle');
    const modalFooter = modal.querySelector('.modal-footer');

    // Change Warning to Success Style
    modalHeader.innerText = "Action Successful!";
    warningIcon.className = "fa fa-check-circle";
    warningIcon.parentElement.style.background = "#e8f5e9"; // Light green
    warningIcon.style.color = "#4BB543";
    
    // Set the message
    titleSpan.innerText = message.replace(/\+/g, ' ');
    titleSpan.style.color = "#592d3e";
    
    // Hide the "Yes, Delete" button and change "Cancel" to "Close"
    confirmBtn.style.display = "none";
    const cancelBtn = modal.querySelector('.btn-cancel');
    cancelBtn.innerText = "Close";
    cancelBtn.style.width = "100%";

    modal.classList.add('active');
}

// 3. CLOSE LOGIC
function closeModal() {
    const modal = document.getElementById('deleteModal');
    if(modal) {
        modal.classList.remove('active');
        // Clean the URL so the popup doesn't appear again on refresh
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

// 4. AUTO-LOADER
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');

    if (msg) {
        showNotificationPopup(msg);
    }
});

// Close on outside click
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeModal();
    }
};
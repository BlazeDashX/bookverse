/**
 * BOOKVERSE POPUP SYSTEM
 */

// Delete Confirmation
function confirmDelete(id, title) {
    const modal = document.getElementById('deleteModal');
    const titleSpan = document.getElementById('bookTitleDisplay');
    const confirmBtn = document.getElementById('confirmDeleteBtn');

    if (modal && titleSpan && confirmBtn) {
        titleSpan.innerText = `"${title}"`;
        // Point to the controller
        confirmBtn.href = `../../controller/deleteBookController.php?id=${id}`;
        modal.classList.add('active');
    }
}

// Notification Popup (Reuses Delete Modal)
function showNotificationPopup(message) {
    const modal = document.getElementById('deleteModal');
    const titleSpan = document.getElementById('bookTitleDisplay');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const modalHeader = modal.querySelector('h3');
    const warningIcon = modal.querySelector('.fa-exclamation-triangle');
    const cancelBtn = modal.querySelector('.btn-cancel');

    // Update Header to Success
    if (modalHeader) modalHeader.innerText = "Action Successful!";

    // Update Icon
    if (warningIcon) {
        warningIcon.className = "fa fa-check-circle";
        warningIcon.style.color = "#4BB543";
        // Update circle background if parent exists
        if (warningIcon.parentElement) {
            warningIcon.parentElement.style.background = "#e8f5e9";
        }
    }

    // Set Message
    if (titleSpan) {
        titleSpan.innerText = message.replace(/\+/g, ' ');
        titleSpan.style.color = "#592d3e";
    }

    // Hide Delete Button & Update Close Button
    if (confirmBtn) confirmBtn.style.display = "none";
    if (cancelBtn) {
        cancelBtn.innerText = "Close";
        cancelBtn.style.width = "100%";
    }

    modal.classList.add('active');
}

// Close Modal Logic
function closeModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.remove('active');
        // Clean URL to prevent popup on refresh
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

// Auto-load notification from URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    if (msg) showNotificationPopup(msg);
});

// Close on outside click
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeModal();
    }
};
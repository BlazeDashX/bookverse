document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const modal = document.getElementById('checkoutModal');
    const successModal = document.getElementById('successModal');
    const checkoutForm = document.getElementById('checkoutForm');

    // Open Checkout Modal (Global for HTML access)
    window.openCheckout = function(id, title, price, type) {
        document.getElementById('inputBookId').value = id;
        document.getElementById('inputType').value = type;

        // Update UI
        const action = (type === 'buy' ? 'Buying: ' : 'Renting: ');
        document.getElementById('modalBookTitle').innerText = action + title;
        document.getElementById('modalPrice').innerText = "$" + parseFloat(price).toFixed(2);

        // Reset Form
        checkoutForm.reset();
        document.querySelectorAll('.checkout-input').forEach(el => el.classList.remove('error'));
        modal.classList.add('active');
    };

    // Close Modal
    window.closeCheckout = function() {
        modal.classList.remove('active');
    };

    // Success Redirect
    window.redirectToShelf = function() {
        window.location.href = "user_dashboard.php?msg=Purchase+Successful!&type=success";
    };

    // Close on outside click
    window.onclick = function(event) {
        if (event.target === modal) closeCheckout();
    };

    // Handle Transaction
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let isValid = true;

            // Reset Errors
            document.querySelectorAll('.checkout-input').forEach(el => el.classList.remove('error'));

            // Validate Card Name
            const cardName = document.querySelector('input[name="card_holder"]');
            if (cardName.value.trim().length < 3) {
                isValid = false;
                cardName.classList.add('error');
            }

            // Validate Card Number (16 digits)
            const cardNum = document.querySelector('input[name="card_number"]');
            const cleanNum = cardNum.value.replace(/\s/g, '');
            if (!/^\d{16}$/.test(cleanNum)) {
                isValid = false;
                cardNum.classList.add('error');
            }

            // Validate Expiry (MM/YY)
            const expiry = document.querySelector('input[name="expiry_date"]');
            if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry.value)) {
                isValid = false;
                expiry.classList.add('error');
            }

            // Validate CVC (3-4 digits)
            const cvc = document.querySelector('input[name="cvc"]');
            if (!/^\d{3,4}$/.test(cvc.value)) {
                isValid = false;
                cvc.classList.add('error');
            }

            if (!isValid) {
                alert("Please check your payment details.");
                return;
            }

            // Process Transaction
            const formData = new FormData(checkoutForm);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../../controller/store_controller.php", true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        if (res.status === "success") {
                            closeCheckout();
                            successModal.classList.add('active');
                        } else {
                            alert(res.message || "Transaction failed.");
                        }
                    } catch (e) {
                        alert("Server error occurred.");
                    }
                }
            };
            xhr.send(formData);
        });
    }
});
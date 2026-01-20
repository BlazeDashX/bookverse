document.addEventListener("DOMContentLoaded", function () {
    const usernameEl = document.getElementById("username");
    const emailEl = document.getElementById("email");
    const passwordEl = document.getElementById("password");
    const confirmEl = document.getElementById("confirmPassword");
    const resultMsg = document.getElementById("resultMsg");

    const errUsername = document.getElementById("errUsername");
    const errEmail = document.getElementById("errEmail");
    const errPassword = document.getElementById("errPassword");
    const errConfirm = document.getElementById("errConfirm");

    function clearErrors() {
        errUsername.innerText = "";
        errEmail.innerText = "";
        errPassword.innerText = "";
        errConfirm.innerText = "";
        errUsername.style.display = "none";
        errEmail.style.display = "none";
        errPassword.style.display = "none";
        errConfirm.style.display = "none";
        usernameEl.classList.remove("input-error");
        emailEl.classList.remove("input-error");
        passwordEl.classList.remove("input-error");
        confirmEl.classList.remove("input-error");
        resultMsg.innerText = "";
    }

    function showError(el, errEl, msg) {
        errEl.innerText = msg;
        errEl.style.display = "block";
        el.classList.add("input-error");
    }

    function isValidEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
    function isValidUsername(u) { return /^[a-zA-Z0-9_]{3,20}$/.test(u); }

    document.getElementById("registerForm").addEventListener("submit", function (e) {
        e.preventDefault();
        clearErrors();

        const username = usernameEl.value.trim();
        const email = emailEl.value.trim();
        const password = passwordEl.value;
        const confirmPassword = confirmEl.value;

        let hasError = false;

        if (username === "") {
            showError(usernameEl, errUsername, "Username is required.");
            hasError = true;
        } else if (!isValidUsername(username)) {
            showError(usernameEl, errUsername, "Username: 3-20 characters.");
            hasError = true;
        }

        if (email === "") {
            showError(emailEl, errEmail, "Email is required.");
            hasError = true;
        } else if (!isValidEmail(email)) {
            showError(emailEl, errEmail, "Invalid email format.");
            hasError = true;
        }

        if (password === "") {
            showError(passwordEl, errPassword, "Password is required.");
            hasError = true;
        } else if (password.length < 6) {
            showError(passwordEl, errPassword, "Min 6 characters.");
            hasError = true;
        }

        if (confirmPassword === "") {
            showError(confirmEl, errConfirm, "Confirm password required.");
            hasError = true;
        } else if (password !== "" && password !== confirmPassword) {
            showError(confirmEl, errConfirm, "Passwords do not match.");
            hasError = true;
        }

        if (hasError) return;

        let data = {
            username: username,
            email: email,
            password: password,
            confirmPassword: confirmPassword
        };

        let xhttp = new XMLHttpRequest();
        // Updated path relative to /views/auth/register.php
        xhttp.open("POST", "../../controller/auth/register_controller.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    let response = JSON.parse(this.responseText);
                    if (response.status === "field_error" && response.errors) {
                        if (response.errors.username) showError(usernameEl, errUsername, response.errors.username);
                        if (response.errors.email) showError(emailEl, errEmail, response.errors.email);
                        if (response.errors.password) showError(passwordEl, errPassword, response.errors.password);
                        if (response.errors.confirmPassword) showError(confirmEl, errConfirm, response.errors.confirmPassword);
                    } else if (response.status === "success") {
                        alert("Account created! Redirecting...");
                        window.location.href = "login.php";
                    } else {
                        resultMsg.innerText = response.message || "Error occurred.";
                        resultMsg.style.color = "red";
                    }
                } catch (e) {
                    console.error("Invalid JSON from server:", this.responseText);
                }
            }
        };
        xhttp.send("data=" + encodeURIComponent(JSON.stringify(data)));
    });
});
document.addEventListener("DOMContentLoaded", function() {
    const userEl = document.getElementById("usernameOrEmail");
    const passEl = document.getElementById("password");
    const errUser = document.getElementById("errUser");
    const errPass = document.getElementById("errPass");

    // Display error message
    function showError(el, errEl, msg) {
        errEl.innerText = msg;
        errEl.style.display = "block";
        el.classList.add("input-error");
    }

    // Clear all errors
    function clearErrors() {
        errUser.style.display = "none";
        errPass.style.display = "none";
        userEl.classList.remove("input-error");
        passEl.classList.remove("input-error");
    }

    // Handle Form Submission
    document.getElementById("loginForm").addEventListener("submit", function(e) {
        e.preventDefault();
        clearErrors();

        const data = {
            usernameOrEmail: userEl.value.trim(),
            password: passEl.value
        };

        const xhttp = new XMLHttpRequest();
        xhttp.open("POST", "../../controller/auth/login_controller.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    const res = JSON.parse(this.responseText);

                    if (res.status === "field_error") {
                        if (res.errors.usernameOrEmail) showError(userEl, errUser, res.errors.usernameOrEmail);
                        if (res.errors.password) showError(passEl, errPass, res.errors.password);
                    } else if (res.status === "success") {
                        // Redirect based on role
                        window.location.href = (res.role === "admin") ? 
                            "../admin/admin_dashboard.php" : 
                            "../user/user_dashboard.php";
                    } else {
                        alert(res.message);
                    }
                } catch (e) {
                    console.error("JSON Parse Error");
                }
            }
        };

        // Send data as JSON string
        xhttp.send("data=" + encodeURIComponent(JSON.stringify(data)));
    });
});
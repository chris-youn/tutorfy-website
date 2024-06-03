document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("login");
    const userpassError = document.getElementById("userpassError");

    form.addEventListener("submit", function(event) {
        event.preventDefault();

        const username = document.getElementById("username");
        const password = document.getElementById("password");

        let isValid = true;

        userpassError.textContent = "";

        if (username.value.trim() === "") {
            username.setCustomValidity("Please enter a username.");
            isValid = false;
        } else {
            username.setCustomValidity("");
        }

        if (password.value.trim() === "") {
            password.setCustomValidity("Please enter a password.");
            isValid = false;
        } else {
            password.setCustomValidity("");
        }

        if (isValid) {
            // Simulate form submission and redirect
            window.location.href = "forum.php"; 
        } else {
            username.reportValidity();
            password.reportValidity();
        }
    });
});

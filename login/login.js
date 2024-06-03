document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("login");
    const userpassError = document.getElementById("userpassError");
    const referrerInput = document.getElementById("referrer");

    form.addEventListener("submit", function(event) {
        event.preventDefault();

        const username = document.getElementById("username");
        const password = document.getElementById("password");

        let isValid = true;

        // Clear previous error messages
        userpassError.textContent = "";

        // Validate username
        if (username.value.trim() === "") {
            username.setCustomValidity("Please enter a username.");
            isValid = false;
        } else {
            username.setCustomValidity("");
        }

        // Validate password
        if (password.value.trim() === "") {
            password.setCustomValidity("Please enter a password.");
            isValid = false;
        } else {
            password.setCustomValidity("");
        }

        if (isValid) {
            // Create an AJAX request using fetch and Promises
            fetch("login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `username=${encodeURIComponent(username.value)}&password=${encodeURIComponent(password.value)}&referrer=${encodeURIComponent(referrerInput.value)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok.");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = data.referrer; // Redirect to the referrer page
                } else {
                    userpassError.textContent = data.message;
                    userpassError.style.color = "red";
                }
            })
            .catch(error => {
                console.error("Error:", error.message);
            });
        } else {
            // Trigger the browser's built-in validation UI
            username.reportValidity();
            password.reportValidity();
        }
    });
});

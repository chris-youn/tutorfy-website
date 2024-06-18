document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("register");
    const password = document.getElementById("password");

    password.addEventListener("input", validatePassword);

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form submission for validation

        const username = document.getElementById("username");
        const email = document.getElementById("email");
        const passwordConfirm = document.getElementById("passwordConfirm");
        const captcha = document.getElementById("captcha");

        let isValid = true; // Start with the assumption that the form is valid

        // Clear previous error messages
        document.getElementById("usernameError").textContent = "";
        document.getElementById("emailError").textContent = "";
        document.getElementById("passwordError").textContent = "";
        document.getElementById("passwordConfirmError").textContent = "";

        // Email Validation
        if (!email.validity.valid) {
            email.setCustomValidity("Please enter a valid email address.");
            isValid = false;
            console.log("Email is invalid.");
        } else {
            email.setCustomValidity("");
        }

        // Validate Password criteria on submit to ensure messages turn red if not met
        const isPasswordValid = validatePassword(true);
        if (!isPasswordValid) {
            isValid = false;
            console.log("Password criteria not met.");
        }

        // Password Confirmation Validation
        if (password.value !== passwordConfirm.value) {
            document.getElementById("passwordConfirmError").textContent = "Passwords do not match.";
            document.getElementById("passwordConfirmError").style.color = "red";
            isValid = false;
            console.log("Passwords do not match.");
        } else {
            document.getElementById("passwordConfirmError").textContent = "";
        }

        console.log("Form valid:", isValid);

        // If the form is valid, proceed with the AJAX request
        if (isValid) {
            const formData = new FormData(form);
            fetch("register.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = "login.php"; // Redirect to desired page
                } else {
                    if (data.message === "Username is already taken") {
                        document.getElementById("usernameError").textContent = data.message;
                        document.getElementById("usernameError").style.color = "red";
                    } else if (data.message === "User already exists for this email") {
                        document.getElementById("emailError").textContent = data.message;
                        document.getElementById("emailError").style.color = "red";
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(error => console.error("Error:", error));
        } else {
            // Trigger the browser's built-in validation UI
            username.reportValidity();
            email.reportValidity();
            password.reportValidity();
            passwordConfirm.reportValidity();
            captcha.reportValidity();
        }
    });
});

function validatePassword(isSubmit = false) {
    const password = document.getElementById('password').value;
    
    const lowercase = document.getElementById('lowercase');
    const uppercase = document.getElementById('uppercase');
    const number = document.getElementById('number');
    const special = document.getElementById('special');
    const length = document.getElementById('length');

    let isValid = true;

    if (!isSubmit) {
        lowercase.style.color = "black";
        uppercase.style.color = "black";
        number.style.color = "black";
        special.style.color = "black";
        length.style.color = "black";
    }

    if (/(?=.*[a-z])/.test(password)) {
        lowercase.style.color = "green";
    } else {
        if (isSubmit) lowercase.style.color = "red";
        isValid = false;
    }

    if (/(?=.*[A-Z])/.test(password)) {
        uppercase.style.color = "green";
    } else {
        if (isSubmit) uppercase.style.color = "red";
        isValid = false;
    }

    if (/(?=.*\d)/.test(password)) {
        number.style.color = "green";
    } else {
        if (isSubmit) number.style.color = "red";
        isValid = false;
    }

    if (/(?=.*[@$!%*?&])/.test(password)) {
        special.style.color = "green";
    } else {
        if (isSubmit) special.style.color = "red";
        isValid = false;
    }

    if (password.length >= 8) {
        length.style.color = "green";
    } else {
        if (isSubmit) length.style.color = "red";
        isValid = false;
    }

    console.log("Password valid:", isValid);
    return isValid;
}
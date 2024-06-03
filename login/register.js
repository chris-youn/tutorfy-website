document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("register");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form submission for validation

        const username = document.getElementById("username");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
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
        } else {
            email.setCustomValidity("");
        }

        // Password Validation
        const passwordPatternLower = /(?=.*[a-z])/;
        const passwordPatternUpper = /(?=.*[A-Z])/;
        const passwordPatternDigit = /(?=.*\d)/;
        const passwordPatternSpecial = /(?=.*[@$!%*?&])/;
        const passwordError = document.getElementById('passwordError');

        let errorMessages = [];

        if (!passwordPatternLower.test(password.value)) {
            errorMessages.push("Password must include at least one lowercase letter.");
        }
        if (!passwordPatternUpper.test(password.value)) {
            errorMessages.push("Password must include at least one uppercase letter.");
        }
        if (!passwordPatternDigit.test(password.value)) {
            errorMessages.push("Password must include at least one number.");
        }
        if (!passwordPatternSpecial.test(password.value)) {
            errorMessages.push("Password must include at least one special character (@$!%*?&).");
        }
        if (password.value.length < 8) {
            errorMessages.push("Password must be at least 8 characters long.");
        }

        if (errorMessages.length > 0) {
            passwordError.innerHTML = "<ul><li>" + errorMessages.join("</li><li>") + "</li></ul>";
            isValid = false;
        } else {
            passwordError.innerHTML = ""; 
        }

        // Password Confirmation Validation
        if (password.value !== passwordConfirm.value) {
            document.getElementById("passwordConfirmError").textContent = "Passwords do not match.";
            isValid = false;
        } else {
            document.getElementById("passwordConfirmError").textContent = "";
        }

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
                    } else if (data.message === "User already exists for this email") {
                        document.getElementById("emailError").textContent = data.message;
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

function validatePassword() {
    const password = document.getElementById('password').value;
    
    const lowercase = document.getElementById('lowercase');
    const uppercase = document.getElementById('uppercase');
    const number = document.getElementById('number');
    const special = document.getElementById('special');
    const length = document.getElementById('length');

    if (/(?=.*[a-z])/.test(password)) {
        lowercase.classList.remove('invalid');
        lowercase.classList.add('valid');
    } else {
        lowercase.classList.remove('valid');
        lowercase.classList.add('invalid');
    }

    if (/(?=.*[A-Z])/.test(password)) {
        uppercase.classList.remove('invalid');
        uppercase.classList.add('valid');
    } else {
        uppercase.classList.remove('valid');
        uppercase.classList.add('invalid');
    }

    if (/(?=.*\d)/.test(password)) {
        number.classList.remove('invalid');
        number.classList.add('valid');
    } else {
        number.classList.remove('valid');
        number.classList.add('invalid');
    }

    if (/(?=.*[@$!%*?&])/.test(password)) {
        special.classList.remove('invalid');
        special.classList.add('valid');
    } else {
        special.classList.remove('valid');
        special.classList.add('invalid');
    }

    if (password.length >= 8) {
        length.classList.remove('invalid');
        length.classList.add('valid');
    } else {
        length.classList.remove('valid');
        length.classList.add('invalid');
    }
}

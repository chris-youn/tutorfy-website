document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("payment");

    function validateField(field, pattern, message) {
        if (!pattern.test(field.value)) {
            field.setCustomValidity(message);
            return false;
        } else {
            field.setCustomValidity("");
            return true;
        }
    }

    function validateForm() {
        let isValid = true; // Start with the assumption that the form is valid

        const fullName = document.getElementById("fullName");
        const email = document.getElementById("email");
        const cardNo = document.getElementById("cardNo");
        const cardDate = document.getElementById("cardDate");
        const cvc = document.getElementById("cvcInput");

        // Full Name Validation
        const nameParts = fullName.value.trim().split(" ");
        if (nameParts.length < 2) {
            fullName.setCustomValidity("Please enter your full name with at least two parts.");
            isValid = false;
        } else {
            fullName.setCustomValidity("");
        }

        // Email Validation
        isValid &= validateField(email, /^[^\s@]+@[^\s@]+\.[^\s@]+$/, "Please enter a valid email address.");

        // Card Number Validation
        isValid &= validateField(cardNo, /^(\d{4} ){3}\d{4}$/, "Please enter a valid card number in the format 1234 1234 1234 1234.");

        // Card Expiration Date Validation
        isValid &= validateField(cardDate, /^(0[1-9]|1[0-2])\/\d{2}$/, "Please enter a valid expiration date in the format MM/YY.");

        // CVC Validation
        isValid &= validateField(cvc, /^\d{3}$/, "Please enter a valid 3-digit CVC.");

        return !!isValid;
    }

    function formatCardNumber(event) {
        const input = event.target;
        const value = input.value.replace(/\D/g, '').substring(0, 16); // Remove non-digits and limit to 16 characters
        const formattedValue = value.match(/.{1,4}/g)?.join(' ') || value; // Add spaces every 4 digits
        input.value = formattedValue;
    }

    function formatCardDate(event) {
        const input = event.target;
        const value = input.value.replace(/\D/g, '').substring(0, 4); // Remove non-digits and limit to 4 characters
        let formattedValue = value;
        if (value.length >= 3) {
            formattedValue = value.substring(0, 2) + '/' + value.substring(2, 4); // Add slash after the first two digits
        }
        input.value = formattedValue;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission for validation

        if (validateForm()) {
            sessionStorage.clear();
            form.submit(); // Submit the form if all validations pass
        } else {
            // Trigger the browser's built-in validation UI
            document.querySelectorAll("input").forEach(input => input.reportValidity());
        }
    });

    document.querySelectorAll("input").forEach(input => {
        input.addEventListener("input", validateForm);
    });

    document.getElementById("cardNo").addEventListener("input", formatCardNumber);
    document.getElementById("cardDate").addEventListener("input", formatCardDate);

    renderCartItems();
});

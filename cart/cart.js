function renderCartItems() {
    const cartSummaryItems = document.getElementById('cart-summary-items');
    const cartTotalText = document.getElementById('cart-total-text');
    cartTotalText.innerHTML = "Total: $" + sessionStorage.getItem("total");
    cartSummaryItems.innerHTML = '';

    if (sessionStorage.getItem('tutorSessionShort')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "1hr Tutor Session(s) x" + sessionStorage.getItem('tutorSessionShort') + " Price: $" + sessionStorage.getItem('tutorSessionShort') * 40 +
            `<button id="tutorSessionClear" onclick="clearItem('tutorSessionShort');renderCartItems()">X</button>
            <button id="tutorSessionRemove" onclick="removeFromCart('tutorSessionShort');renderCartItems()">-</button>
            <button id="tutorSessionAdd" onclick="addToCart('tutorSessionShort',1);renderCartItems()">+</button>`;
        cartSummaryItems.appendChild(newItem);
    }
    if (sessionStorage.getItem('tutorSessionLong')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "2hr Tutor Session(s) x" + sessionStorage.getItem('tutorSessionLong') + " Price: $" + sessionStorage.getItem('tutorSessionLong') * 70 +
            `<button id="tutorSessionClear" onclick="clearItem('tutorSessionLong');renderCartItems()">X</button>
            <button id="tutorSessionRemove" onclick="removeFromCart('tutorSessionLong');renderCartItems()">-</button>
            <button id="tutorSessionAdd" onclick="addToCart('tutorSessionLong',1);renderCartItems()">+</button>`;
        cartSummaryItems.appendChild(newItem);
    }
    if (sessionStorage.getItem('tutorSessionShortBulk')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "10hr Tutor Session(s) x" + sessionStorage.getItem('tutorSessionShortBulk') + " Price: $" + sessionStorage.getItem('tutorSessionShortBulk') * 170 +
            `<button id="tutorSessionClear" onclick="clearItem('tutorSessionShortBulk');renderCartItems()">X</button>
            <button id="tutorSessionRemove" onclick="removeFromCart('tutorSessionShortBulk');renderCartItems()">-</button>
            <button id="tutorSessionAdd" onclick="addToCart('tutorSessionShortBulk',1);renderCartItems()">+</button>`;
        cartSummaryItems.appendChild(newItem);
    }
    if (sessionStorage.getItem('tutorSessionLongBulk')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "20hr Tutor Session(s) x" + sessionStorage.getItem('tutorSessionLongBulk') + " Price: $" + sessionStorage.getItem('tutorSessionLongBulk') * 300 +
            `<button id="tutorSessionClear" onclick="clearItem('tutorSessionLongBulk');renderCartItems()">X</button>
            <button id="tutorSessionRemove" onclick="removeFromCart('tutorSessionLongBulk');renderCartItems()">-</button>
            <button id="tutorSessionAdd" onclick="addToCart('tutorSessionLongBulk',1);renderCartItems()">+</button>`;
        cartSummaryItems.appendChild(newItem);
    }
}

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

    renderCartItems();
});

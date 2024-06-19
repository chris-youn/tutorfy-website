document.addEventListener("DOMContentLoaded", function() {
    const cartSummaryItems = document.getElementById('orderSummaryItems');
    const cartIcon = document.getElementById('cartIcon');
    const cartBadge = document.getElementById('cartBadge');
    const cartTotalText = document.getElementById('totalText');
    const cartItems = document.getElementById('items');

    let subtotal = 0;

    function createItemElement(description, quantity, price) {
        let newItem = document.createElement("li");
        newItem.innerHTML = `${description} x ${quantity} - Price: $${(price * quantity).toFixed(2)}`;
        cartSummaryItems.appendChild(newItem);
        subtotal += price * quantity;
    }

    // Fetch and display cart items from local storage
    if (sessionStorage.getItem('tutorSessionShort')) {
        createItemElement("1hr Tutor Session(s)", sessionStorage.getItem('tutorSessionShort'), 40);
    }
    if (sessionStorage.getItem('tutorSessionLong')) {
        createItemElement("2hr Tutor Session(s)", sessionStorage.getItem('tutorSessionLong'), 70);
    }
    if (sessionStorage.getItem('tutorSessionShortBulk')) {
        createItemElement("5 x 1hr Tutor Session(s)", sessionStorage.getItem('tutorSessionShortBulk'), 170);
    }
    if (sessionStorage.getItem('tutorSessionLongBulk')) {
        createItemElement("5 x 2hr Tutor Session(s)", sessionStorage.getItem('tutorSessionLongBulk'), 300);
    }

    // Display subtotal
    let subtotalElement = document.createElement("div");
    subtotalElement.classList.add("subTotalText");
    subtotalElement.innerHTML = `Subtotal: $${subtotal.toFixed(2)}`;
    cartSummaryItems.appendChild(subtotalElement);

    // Calculate and display discount if applicable
    if (sessionStorage.getItem('discountedTotal')) {
        let discountedTotal = parseFloat(sessionStorage.getItem('discountedTotal'));
        let discountValue = (subtotal - discountedTotal).toFixed(2);

        let discountElement = document.createElement("div");
        discountElement.classList.add("discountText");
        discountElement.innerHTML = `Discount: $${discountValue}`;
        cartSummaryItems.appendChild(discountElement);

        // Display total
        let totalElement = document.createElement("div");
        totalElement.classList.add("totalText");
        totalElement.innerHTML = `Total: $${discountedTotal.toFixed(2)}`;
        cartSummaryItems.appendChild(totalElement);
    } else {
        // Display total as subtotal if no discount
        let totalElement = document.createElement("div");
        totalElement.classList.add("totalText");
        totalElement.innerHTML = `Total: $${subtotal.toFixed(2)}`;
        cartSummaryItems.appendChild(totalElement);
    }

    // Clear local storage after displaying the order summary
    sessionStorage.clear();

    // Update the shopping cart in the header
    cartBadge.textContent = "0";
    cartTotalText.textContent = "Total: $0";
    cartItems.innerHTML = "";
});

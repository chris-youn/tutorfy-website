document.addEventListener("DOMContentLoaded", function() {
    cartSummaryItems = document.getElementById('orderSummary');
    if (sessionStorage.getItem('tutorSessionShort')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "1hr Tutor Session(s) x" + sessionStorage.getItem('tutorSessionShort') + " Price: $" + sessionStorage.getItem('tutorSessionShort') * 40
        cartSummaryItems.appendChild(newItem);
    }
    if (sessionStorage.getItem('tutorSessionLong')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "2hr Tutor Session(s) x" + sessionStorage.getItem('tutorSessionLong') + " Price: $" + sessionStorage.getItem('tutorSessionLong') * 70
            
        cartSummaryItems.appendChild(newItem);
    }
    if (sessionStorage.getItem('tutorSessionShortBulk')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "5 x 1hr Tutor Session(s) x" + sessionStorage.getItem('tutorSessionShortBulk') + " Price: $" + sessionStorage.getItem('tutorSessionShortBulk') * 170 
            
        cartSummaryItems.appendChild(newItem);
    }
    if (sessionStorage.getItem('tutorSessionLongBulk')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "5 x 2hr Tutor Session(s) x" + sessionStorage.getItem('tutorSessionLongBulk') + " Price: $" + sessionStorage.getItem('tutorSessionLongBulk') * 300
        cartSummaryItems.appendChild(newItem);
    }
    if (sessionStorage.getItem('total')) {
        let total = document.createElement("div");
        total.classList.add("subTotalText");
        total.innerHTML = "Subtotal: $"+ sessionStorage.getItem('total');
        cartSummaryItems.appendChild(total);
    }
    let discount = document.createElement("div");
    discount.classList.add("discountText");
    discount.innerHTML = "Discount: $"+ (sessionStorage.getItem('total') - sessionStorage.getItem('discountedTotal'));
    let total = document.createElement("div");
    total.classList.add("totalText");
    total.innerHTML = "Total: $" + sessionStorage.getItem('discountedTotal');
    cartSummaryItems.appendChild(discount);
    cartSummaryItems.appendChild(total);

    //TODO, figure out how to do this without causing issues on refresh. sessionStorage.clear();

})



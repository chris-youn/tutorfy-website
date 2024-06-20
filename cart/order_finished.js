function moveSessionStorageToLocalStorage() {
    if (sessionStorage.length === 0) {
      console.log('sessionStorage is already empty.');
      return;
    }
    if (sessionStorage.length === 1 && sessionStorage.getItem('total') === '0') {
        console.log('sessionStorage contains only "total" with value "0". No transfer needed.');
        return;
      }
    localStorage.clear();
  
    for (let i = 0; i < sessionStorage.length; i++) {
      let key = sessionStorage.key(i);
      let value = sessionStorage.getItem(key);
  
      localStorage.setItem(key, value);
    }
   
    sessionStorage.clear();
  
    console.log('sessionStorage items have been transferred to localStorage.');
}

document.addEventListener("DOMContentLoaded", function() {
    
    moveSessionStorageToLocalStorage();
    let separatorDiv = document.createElement("div");
    separatorDiv.classList.add("separator");
    separatorDiv.innerHTML = "<br>";
    cartSummaryItems = document.getElementById('orderSummaryItems');
    cartSummaryItems.appendChild(separatorDiv);
    
    if (localStorage.getItem('tutorSessionShort')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "1hr Tutor Session(s) x" + localStorage.getItem('tutorSessionShort') + " Price: $" + localStorage.getItem('tutorSessionShort') * 40
        cartSummaryItems.appendChild(newItem);
    }

    if (localStorage.getItem('tutorSessionLong')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "2hr Tutor Session(s) x" + localStorage.getItem('tutorSessionLong') + " Price: $" + localStorage.getItem('tutorSessionLong') * 70
            
        cartSummaryItems.appendChild(newItem);
    }

    if (localStorage.getItem('tutorSessionShortBulk')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "5 x 1hr Tutor Session(s) x" + localStorage.getItem('tutorSessionShortBulk') + " Price: $" + localStorage.getItem('tutorSessionShortBulk') * 170 
            
        cartSummaryItems.appendChild(newItem);
    }
    
    if (localStorage.getItem('tutorSessionLongBulk')) {
        let newItem = document.createElement("li");
        newItem.innerHTML = "5 x 2hr Tutor Session(s) x" + localStorage.getItem('tutorSessionLongBulk') + " Price: $" + localStorage.getItem('tutorSessionLongBulk') * 300
        cartSummaryItems.appendChild(newItem);
    }

    if (localStorage.getItem('total')) {
        let total = document.createElement("div");
        total.classList.add("subTotalText");
        total.innerHTML = "Subtotal: $"+ localStorage.getItem('total');
        cartSummaryItems.appendChild(total);
    }

    let discount = document.createElement("div");
    discount.classList.add("discountText");
    if (localStorage.getItem('discountedTotal') == null ||localStorage.getItem('discountedTotal') == 0) {
        discount.innerHTML = "Discount: $0"
    } else {
        discount.innerHTML = "Discount: $"+ (localStorage.getItem('total') - localStorage.getItem('discountedTotal'));
    }
   
    let discountTotal = document.createElement("div");
    discountTotal.classList.add("totalText");
    
    if (localStorage.getItem('discountedTotal') == null ||localStorage.getItem('discountedTotal') == 0) {
        discountTotal.innerHTML = "Total: $" + localStorage.getItem('total');
    } else {
        discountTotal.innerHTML = "Total: $" + localStorage.getItem('discountedTotal')
    }
     
    cartSummaryItems.appendChild(discount);
    cartSummaryItems.appendChild(separatorDiv);
    cartSummaryItems.appendChild(discountTotal);
    
    getNoOfItems();
})
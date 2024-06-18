function getNoOfItems() {
    let shortSession = sessionStorage.getItem("tutorSessionShort");
    let longSession = sessionStorage.getItem("tutorSessionLong");
    let shortSessionBulk = sessionStorage.getItem("tutorSessionShortBulk");
    let longSessionBulk = sessionStorage.getItem("tutorSessionLongBulk");
    if ((parseInt(shortSession) || 0) + (parseInt(longSession)||0) + (parseInt(shortSessionBulk)||0) + (parseInt(longSessionBulk)||0) == 0) {
        document.getElementById("cartBadge").style.display = "none";
    } else {
        document.getElementById("cartBadge").style.display = "inline-block";
        document.getElementById("cartBadge").innerHTML = (parseInt(shortSession) || 0) + (parseInt(longSession)||0) + (parseInt(shortSessionBulk)||0) + (parseInt(longSessionBulk)||0);

    }
}

function addToCart(item, quantity) {
    let currentQuantity = parseInt(sessionStorage.getItem(item))|| 0;
    sessionStorage.setItem(item, currentQuantity + quantity);
    updateTotal();
    updateCart();
    getNoOfItems();
}

function updateTotal() {
    let total = 0;
    let shortSession = sessionStorage.getItem("tutorSessionShort");
    let longSession = sessionStorage.getItem("tutorSessionLong");
    let shortSessionBulk = sessionStorage.getItem("tutorSessionShortBulk");
    let longSessionBulk = sessionStorage.getItem("tutorSessionLongBulk");
    total += shortSession * 40;
    total += longSession * 70;
    total += shortSessionBulk * 170;
    total += longSessionBulk * 300;

    document.getElementById('totalText').innerHTML = "Total: $" + total;
    sessionStorage.setItem("total", total);
    console.log(sessionStorage.getItem("total"))
    updateCart();
}

function updateCart() {
    let listItem = document.getElementById('tutorSessionListItem')
    let listItemText = document.getElementById('tutorSessionCartShort');

    let listItemLong = document.getElementById('tutorSessionLongListItem');
    let listItemLongText = document.getElementById('tutorSessionCartLong');

    let listItemShortBulk = document.getElementById('tutorSessionShortBulkListItem');
    let listItemShortBulkText = document.getElementById('tutorSessionCartShortBulk');

    let listItemLongBulk = document.getElementById('tutorSessionLongBulkListItem');
    let listItemLongBulkText = document.getElementById('tutorSessionCartLongBulk');
    let type = sessionStorage.getItem('tutorSession') || 0;
    if (sessionStorage.getItem("tutorSessionShort")) {
        listItem.style.display="flex";
        listItemText.innerHTML = "Tutor Session(s) QTY: "+ sessionStorage.getItem("tutorSessionShort") + " Price: $"+ sessionStorage.getItem("tutorSessionShort")*40;
    } else {
        listItem.style.display="none";
    }

    if (sessionStorage.getItem("tutorSessionLong")) {
        listItemLong.style.display="flex";
        listItemLongText.innerHTML = "Tutor Session(s) QTY: "+ sessionStorage.getItem("tutorSessionLong")
         + " Price: $"+ sessionStorage.getItem("tutorSessionLong")*70;
    } else {
        listItemLong.style.display="none";
    }

    if (sessionStorage.getItem("tutorSessionShortBulk")) {
        listItemShortBulk.style.display="flex";
        listItemShortBulkText.innerHTML = "Tutor Session(s) QTY: "+ sessionStorage.getItem("tutorSessionShortBulk")
         + " Price: $"+ sessionStorage.getItem("tutorSessionShortBulk")*170;
    } else {
        listItemShortBulk.style.display="none";
    }
    if (sessionStorage.getItem("tutorSessionLongBulk")) {
        listItemLongBulk.style.display="flex";
        listItemLongBulkText.innerHTML = "Tutor Session(s) QTY: "+ sessionStorage.getItem("tutorSessionLongBulk")
         + " Price: $"+ sessionStorage.getItem("tutorSessionLongBulk")*300;
    } else {
        listItemLongBulk.style.display="none";
    }
}

function removeFromCart(item) {
    let currentQuantity = parseInt(sessionStorage.getItem(item))|| 0;
    sessionStorage.setItem(item, currentQuantity - 1);
    if (currentQuantity == 1) {
        clearItem(item);
    }
    updateTotal();
    updateCart();
    getNoOfItems();
    updateTotal();
}

function clearItem(item) {
    console.log("test")
    sessionStorage.removeItem(item);
    document.getElementById('tutorSessionListItem').style.display = 'none';
    updateTotal();
    getNoOfItems();
} 

window.onscroll = function() {
    const scrollButton = document.querySelector('.scroll-to-top');
    if (window.scrollY > 100) {
        scrollButton.style.display = "block";
    } else {
        scrollButton.style.display = "none";
    }
    };

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('tutorSessionClear').addEventListener('click', function () {
        clearItem("tutorSessionShort");
    });
    document.getElementById('tutorSessionLongClear').addEventListener('click', function () {
        clearItem("tutorSessionLong");
    });
    document.getElementById('tutorSessionShortBulkClear').addEventListener('click', function () {
        clearItem("tutorSessionShortBulk");
    });
    document.getElementById('tutorSessionLongBulkClear').addEventListener('click', function () {
        clearItem("tutorSessionLongBulk");

    });

    document.getElementById('tutorSessionAdd').addEventListener('click', function () {
        addToCart("tutorSessionShort",1);
    });
    document.getElementById('tutorSessionLongAdd').addEventListener('click', function () {
        addToCart("tutorSessionLong", 1);
    });

    document.getElementById('tutorSessionShortBulkAdd').addEventListener('click', function () {
        addToCart("tutorSessionShortBulk",1);
    });
    document.getElementById('tutorSessionLongBulkAdd').addEventListener('click', function () {
        addToCart("tutorSessionLongBulk",1);
    });


    document.getElementById('tutorSessionRemove').addEventListener('click', function () {
        removeFromCart("tutorSessionShort");
    });
    document.getElementById('tutorSessionLongRemove').addEventListener('click', function () {
        removeFromCart("tutorSessionLong");
    });

    document.getElementById('tutorSessionShortBulkRemove').addEventListener('click', function () {
       removeFromCart("tutorSessionShortBulk");
    });
    document.getElementById('tutorSessionLongBulkRemove').addEventListener('click', function () {
        removeFromCart("tutorSessionLongBulk");
    });

    updateTotal();
    getNoOfItems();
    document.querySelector('.shopping-cart').style.display = 'none';

    document.getElementById('cartIcon').addEventListener('click', function () {
        var cart = document.getElementById('shopping-cart');
        cart.style.display = (cart.style.display === 'none' || cart.style.display === '') ? 'block' : 'none';
    });
    console.log(sessionStorage.getItem("tutorSessionShort"))
    if (sessionStorage.getItem("tutorSessionShort")=="undefined") {
        document.getElementById('tutorSessionListItem').style.display = 'none';
        console.log("test")
    } else if (sessionStorage.getItem('tutorSessionShort')>0){
        document.getElementById('tutorSessionListItem').style.display = 'flex';
        
    }

    document.getElementById('tutorSessionClear').addEventListener('click', function () {
        clearItem("tutorSession");
    });

    // Cookie consent functionality
    if (!localStorage.getItem("cookieAccepted") && !localStorage.getItem("cookieDeclined")) {
        setTimeout(() => {
            document.getElementById("cookieConsent").classList.add("show");
        }, 1000);
    }

    document.querySelector(".cookie-accept-btn").addEventListener("click", function () {
        localStorage.setItem("cookieAccepted", "true");
        document.cookie = "userConsent=true; path=/; max-age=" + (60 * 60 * 24 * 365); // Store a cookie for 1 year
        document.getElementById("cookieConsent").classList.remove("show");
    });

    document.querySelector(".cookie-decline-btn").addEventListener("click", function () {
        localStorage.setItem("cookieDeclined", "true");
        document.cookie = "userConsent=false; path=/; max-age=0"; // Expire the cookie immediately
        document.getElementById("cookieConsent").classList.remove("show");
    });

    function setNewCookie(name, value, days) {
        if (localStorage.getItem("cookieAccepted")) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }
    }
});

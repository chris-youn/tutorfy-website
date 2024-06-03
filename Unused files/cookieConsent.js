document.addEventListener("DOMContentLoaded", function() {
    
    // Cookie consent functionality
    if (!localStorage.getItem("cookieAccepted") && !localStorage.getItem("cookieDeclined")) {
        setTimeout(() => {
            document.getElementById("cookieConsent").classList.add("show");
        }, 1000);
    }

    document.querySelector(".cookie-accept-btn").addEventListener("click", function() {
        localStorage.setItem("cookieAccepted", "true");
        document.cookie = "userConsent=true; path=/; max-age=" + (60 * 60 * 24 * 365); // Store a cookie for 1 year
        document.getElementById("cookieConsent").classList.remove("show");
    });

    document.querySelector(".cookie-decline-btn").addEventListener("click", function() {
        localStorage.setItem("cookieDeclined", "true");
        document.cookie = "userConsent=false; path=/; max-age=0"; // Expire the cookie immediately
        document.getElementById("cookieConsent").classList.remove("show");
    });

    // Example function to check consent before storing a new cookie
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

    // Usage example
    setNewCookie("exampleCookie", "exampleValue", 7); // Only sets the cookie if consent was given
});

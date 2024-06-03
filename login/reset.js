document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("resetForm");
    const newPassword = document.getElementById("new_password");
    const confirmPassword = document.getElementById("confirm_password");

    form.addEventListener("submit", function(event) {
        if (newPassword.value !== confirmPassword.value) {
            alert("Passwords do not match.");
            event.preventDefault();
        }
    });
});

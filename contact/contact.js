document.addEventListener('DOMContentLoaded', function(){
    var submitButton = document.getElementById('contactSubmit');
    submitButton.disabled = true;
    var emailValid = false;
    var contentValid = false;
    var nameValid = false;
    var subjectValid = false;
    document.getElementById('emailInput').addEventListener('input', validateEmail);
    document.getElementById('emailInput').addEventListener('change', validateEmail);
    document.getElementById('name').addEventListener('input', validateName);
    document.getElementById('name').addEventListener('change', validateName);
    document.getElementById('subject').addEventListener('input', validateSubject);
    document.getElementById('subject').addEventListener('change', validateSubject);
    document.getElementById('content').addEventListener('input', validateContent);
    document.getElementById('content').addEventListener('change', validateContent);

    function validateEmail () {
        console.log("emailfired")
        var email = document.getElementById('emailInput').value;
        if (email.length > 0) {
            emailValid = true;
        } else {
            emailValid = false;
            submitButton.disabled = true;
        }
        updateValidity();
    }
    
    function validateName () {
        var name = document.getElementById('name').value;
        if (name.length > 0) {
            nameValid = true;
        } else {
            nameValid = false;
           
            submitButton.disabled = true;
        }
        updateValidity();
    }
    function validateSubject () {
        var subject = document.getElementById('subject').value;
        if (subject.length > 0) {
            subjectValid = true;
        } else {
            subjectValid = false;
            submitButton.disabled = true;
        }
        updateValidity();
    }
    function validateContent () {
        var content = document.getElementById('content').value;
        if (content.length > 0) {
            contentValid = true;
        } else {
            contentValid = false;
            submitButton.disabled = true;
        }
        updateValidity();
    }
    function updateValidity() {
        console.log("valid check")
        if (emailValid && nameValid && subjectValid && contentValid) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }
})


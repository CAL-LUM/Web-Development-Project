$(document).ready(function () {
    // Regular expressions for validation
    var nameRegex = /^[a-zA-Z]+$/;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;

    // Function to validate the form
    function validateForm() {
        var firstName = $("input[name='firstName']").val();
        var lastName = $("input[name='lastName']").val();
        var email = $("input[name='emailUser']").val();
        var password = $("input[name='passwordUser']").val();
        var confirmPassword = $("input[name='confirmPasswordUser']").val();

        // Validation
        if (!nameRegex.test(firstName)) {
            alert("Please enter a valid first name.");
            return false;
        }
        if (!nameRegex.test(lastName)) {
            alert("Please enter a valid last name.");
            return false;
        }
        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }
        if (!passwordRegex.test(password)) {
            alert("Password must be between 6 to 20 characters which contain at least one numeric digit, one uppercase and one lowercase letter.");
            return false;
        }
        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        if (!$("input[type='checkbox']").is(":checked")) {
            alert("Please agree to the Terms and Conditions and Privacy Policy.");
            return false;
        }
        return true;
    }

    // Form submission
    $("form.signup-form").submit(function (event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});
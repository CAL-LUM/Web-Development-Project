$(document).ready(function () {
    // Function to validate the police signin form
    function validatePoliceSigninForm() {
        var email = $("input[name='email']").val();
        var password = $("input[name='password']").val();

        // Basic validation for email and password
        if (!email || !password) {
            alert("Please enter both email and password.");
            return false;
        }
        return true;
    }

    // Form submission for police signin
    $("form.login-form.police").submit(function (event) {
        if (!validatePoliceSigninForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});
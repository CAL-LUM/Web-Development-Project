$(document).ready(function () {
    // Function to validate the signin form
    function validateSigninForm() {
        var email = $("input[name='email']").val();
        var password = $("input[name='password']").val();

        // Basic validation for email and password
        if (!email || !password) {
            alert("Please enter both email and password.");
            return false;
        }
        return true;
    }

    // Form submission for signin
    $("form.login-form.public").submit(function (event) {
        if (!validateSigninForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});

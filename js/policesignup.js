// policesignup.js

$(document).ready(function () {
    // Function to validate the police signup form
    function validatePoliceSignupForm() {
        var email = $("input[name='emailPolice']").val();
        var password = $("input[name='passwordPolice']").val();
        var confirmPassword = $("input[name='confirmPasswordPolice']").val();

        // Basic validation for email and password
        if (!email || !password || !confirmPassword) {
            alert("Please fill in all required fields.");
            return false;
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }

        return true;
    }

    // Form submission for police signup
    $("form.register-form").submit(function (event) {
        if (!validatePoliceSignupForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});
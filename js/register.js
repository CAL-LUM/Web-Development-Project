// register.js

$(document).ready(function () {
    // Function to validate the register form
    function validateRegisterForm() {
        var mpn = $("input[name='mpn']").val();
        var brand = $("input[name='brand']").val();
        var model = $("input[name='model']").val();
        var type = $("input[name='type']").val();
        var wheelSize = $("input[name='wheel-size']").val();
        var colour = $("input[name='colour']").val();
        var gears = $("input[name='gears']").val();
        var brakeType = $("input[name='brake-type']").val();
        var suspension = $("input[name='suspension']").val();
        var gender = $("input[name='gender']").val();
        var ageGroup = $("input[name='age-group']").val();

        // Basic validation for required fields
        if (!mpn || !brand || !model || !type || !wheelSize || !colour || !gears || !brakeType || !suspension || !gender || !ageGroup) {
            alert("Please fill in all required fields.");
            return false;
        }

        return true;
    }

    // Form submission for register form
    $("form.register-form").submit(function (event) {
        if (!validateRegisterForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});
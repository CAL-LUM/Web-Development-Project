<?php
// Start / resume session
session_start();

// Check if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html"); // Redirect to sign-in page if not logged in.
    exit(); // Stop further execution
} 
// embed HTML inside .php file
?> 
<!DOCTYPE html>
<html>

<head>
    <!-- Viewport / Language basics -->
    <meta charset="UTF-8"> <!-- Set character encoding to UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define viewport settings for responsive design -->
    <meta http-equiv="X-UA-Compatible" content="IE-Edge"> <!-- Set compatibility mode for Internet Explorer -->
    <title>My bikes</title> <!-- Set the title of the webpage -->
    <link rel="stylesheet" href="../scss/mybikes.css"> <!-- Link css stylesheet -->

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"> <!-- Preconnect to Google Fonts API -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> <!-- Preconnect to Google Fonts API -->
    <link href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;800;900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> <!-- Link to Google Fonts stylesheet for custom fonts -->
</head>

<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navbar-container">
            <!-- Navbar links-->
            <div class="menu">
                <a href="../index.html" class="menu-link">Home</a>
                <a href="../register.html" class="menu-link">Register a bike</a>
                <a href="stolen.php" class="menu-link">Stolen bikes</a>
                <a href="../mybikes.html" class="menu-link">My bikes</a>
            </div>
            <!-- Further links -->
            <div class="menu">
                <a href="../signin.html" class="menu-link">Sign in</a>
                <a href="../signup.html" class="menu-btn">Sign up</a>
                <a href="../policedashboard.html" class="menu-btn-police">Police</a>
            </div>
            <!-- Mini Navbar for signout and profile-->
            <div class="mini-navbar">
                <a href="signout.php" class="mini-menu-btn">Sign out</a>
                <a href="profile.php" class="mini-menu-btn">Profile</a>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="container">

        <!-- Form for reporting stolen bike -->
        <form class="report-form" action="reported.php" method="post">
            <h1>Report your bike as stolen</h1>
            <h2>Enter bike MPN and address of theft below</h2><br>
			<!-- Input fields for report-form, POST data to reported.php for handling -->
            <input type="text" name="MPN" placeholder="Bike MPN" required>
            <input type="text" name="Address1" placeholder="Address 1" required>
            <input type="text" name="Address2" placeholder="Address 2" required>
            <input type="text" name="postcode" placeholder="Postcode" required>
            <input type="text" name="date" placeholder="Date of theft" required>
            <br><button class="form-submit-button update-map-location" type="button">Update Google Maps location</button>
            <br><button class="form-submit-button report-bike-stolen" type="button">Report</button>
        </form>

        <div class="map-container">
            <!-- Google Map will be displayed here -->
        </div>

        <div class="info-container">
            <!-- Bike tiles will are added here dynamically -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <p class="footer-center">&copy; 2024 Bike Theft Prevention. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Jquery libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
    <script src="../js/mybikes.js"></script> 

    <!-- JavaScript for Google Maps -->
    <script>
        var map; // Define map as a global variable  to prevent errors with initmap
        var marker; // Define marker as a global variable to prevent errors with initmap

		// Function to update map location based on address 
		function updateMapLocation(address) {
			// Create a new Geocoder object
			var geocoder = new google.maps.Geocoder();
			// Use the geocode method to convert the address into geographic coordinates
			geocoder.geocode({ 'address': address }, function (results, status) {
				// Check if geocoding was successful
				if (status === 'OK') {
					// If successful, set the map centre to the obtained location
					map.setCenter(results[0].geometry.location);
					// Move the marker to the obtained location
					marker.setPosition(results[0].geometry.location);
				} else {
					// If geocoding was not successful, display an alert with the reason
					alert('Geocode was not successful for the following reason: ' + status);
				}
			});
		}

        // Event listener for updating map location
        $('.update-map-location').on('click', function () {
            // Logic to update map location
            var address = $('.report-form').find('input[name="Address1"]').val() + ', ' +
                $('.report-form').find('input[name="Address2"]').val() + ', ' +
                $('.report-form').find('input[name="postcode"]').val();

            updateMapLocation(address); // Updates default location to re-align the map.
        });

        // Event listener for reporting bike stolen
        $('.report-bike-stolen').on('click', function () {
            // Logic to submit form to 'reported.php'
            $('.report-form').attr('action', 'reported.php').submit();
        });

        // Initialize and add the map
        function initMap() {
            // Default location
            var defaultLocation = { lat: 51.8882, lng: -2.0884 }; // Set default location as waterworth building
            // Initialize the map
            map = new google.maps.Map(document.querySelector('.map-container'), {
                zoom: 12, // Set zoom level
                center: defaultLocation // set centre of map
            });
            // Define the new marker so that the default location is updated.
            marker = new google.maps.Marker ({
                position: defaultLocation, // set marker to default location
                map: map
            });
        }
    </script>
    <!-- Load Google Maps API with callback to initMap function and loading = async to prevent errors-->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9AUezX33t8xF3yyhBQu6tHfW0KeRhZig&callback=initMap&loading=async"></script>
</body>

</html>

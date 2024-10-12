<?php
session_start();

// Check if user is logged in and is a police user
if (!isset($_SESSION['user_id']) || !isset($_SESSION['police_user'])) {
    header("Location: ../policesignin.html"); // re-direct to policesign in if not police user ID.
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Viewport / Language basics -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set viewport settings for device -->
    <meta http-equiv="X-UA-Compatible" content="IE-Edge">
    <title>Stolen Bikes</title> <!-- Set website title -->
    <link rel="stylesheet" href="../scss/stolenbikes.css"> <!-- Link to css stylesheet-->

    <!-- Google fonts and preconnections to google APIs-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;800;900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar section -->
    <div class="navbar">
        <div class="navbar-container">
            <div class="menu">
				<!-- Navbar links-->
                <a href="../index.html" class="menu-link">Home</a>
                <a href="../register.html" class="menu-link">Register a bike</a>
                <a href="stolen.php" class="menu-link">Stolen bikes</a>
                <a href="../mybikes.html" class="menu-link">My bikes</a>
            </div>
            <div class="menu">
                <a href="../signin.html" class="menu-link">Sign in</a>
                <a href="../signup.html" class="menu-btn">Sign up</a>
                <a href="../policedashboard.html" class="menu-btn-police">Police</a>
            </div>
			<!-- mini navbar to handle signing out and profile access -->
            <div class="mini-navbar">
                <a href="signout.php" class="mini-menu-btn">Sign out</a>
                <a href="profile.php" class="mini-menu-btn">Profile</a>
            </div>
        </div>
    </div>

	<!-- Map container section -->
	<div class="map-wrapper">
		<div class="map-container"></div>
		<!-- Google Maps is displayed here -->
	</div>
	
	<!-- Date filter container -->
	<div class="date-filter-container">
		<form id="date-filter-form">
			<!-- Start date and end date inputs for filtering results-->
			<label for="start-date">Start Date:</label>
			<input type="date" id="start-date" name="start_date" value="<?php echo date('Y-m-d'); ?>">
			<label for="end-date">End Date:</label>
			<input type="date" id="end-date" name="end_date" value="<?php echo date('Y-m-d'); ?>">
			<button type="submit">Apply</button>
		</form>
	</div>
	
	<div>
        <div class="info-container">
            <!-- Stolen bike tiles will be added here dynamically -->
        </div>
    </div>

    <!-- Footer section -->
    <footer class="footer">
        <div class="footer-container">
            <p class="footer-center">&copy; 2024 Bike Theft Prevention. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Jquery scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/mybikes.js"></script>

    <!-- JavaScript code for Google Maps -->
    <script>
        // Initialize and add the map
        function initMap() {
            // Set Default location
            var defaultLocation = { lat: 51.8882, lng: -2.0884 };
            // Initialize map variable inside container
            var map = new google.maps.Map(document.querySelector('.map-container'), {
                zoom: 12, // set zoom to 12
                center: defaultLocation // set center to default location
            });

            // Function to update map location based on address
            function updateMapLocation(address) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'address': address }, function (results, status) {
                    if (status === 'OK') {
                        map.setCenter(results[0].geometry.location);
                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }

            // Event listener for buttons to view theft location
            $('.info-container').on('click', '.view-location-btn', function () {
                var address = $(this).data('address');
                updateMapLocation(address);
            });

            // Call function to fetch and display stolen bikes
            fetchAndDisplayStolenBikes();
        }

			// Function to fetch stolen bikes and create tiles, Ajax GET from stolenbikes.php in the JSON data type. 
			function fetchAndDisplayStolenBikes() {
				$.ajax({
					url: '../php/getstolenbikes.php',
					type: 'GET',
					dataType: 'json',
					success: function (data) {
						// Iterate over each stolen bike and create a tile
						data.forEach(function (bike) {
							// Construct HTML for the bike tile
							var bikeTile = '<div class="bike-tile">' +
								'<img src="' + bike.imagePath + '" alt="Bike Image">' +
								'<p>MPN: ' + bike.mpn + '</p>' +
								'<p>Brand: ' + bike.brand + '</p>' +
								'<p>Model: ' + bike.model + '</p>' +
								'<p>Type: ' + bike.type + '</p>' +
								'<p>Wheel Size: ' + bike.wheelSize + '</p>' +
								'<p>Colour: ' + bike.colour + '</p>' +
								'<p>Gears: ' + bike.gears + '</p>' +
								'<p>Brake Type: ' + bike.brakeType + '</p>' +
								'<p>Suspension: ' + bike.suspension + '</p>' +
								'<p>Gender: ' + bike.gender + '</p>' +
								'<p>Age Group: ' + bike.ageGroup + '</p>' +
								'<textarea class="comments-box">' + bike.comments + '</textarea>' +
								'<button class="update-comments-btn" data-stolenBikeID="' + bike.stolenBikeID + '">Update</button>' +
								'<button class="view-location-btn" data-address="' + bike.Address1 + ', ' + bike.Address2 + ', ' + bike.postcode + '">View theft location</button>' +
								'<button class="resolve-investigation-btn" data-stolenBikeID="' + bike.stolenBikeID + '">Investigation resolved</button>' +
								'</div>';

							// Convert the HTML string to a jQuery object
							var $bikeTile = $(bikeTile);

							// Event listener for the update comments button
							$bikeTile.find('.update-comments-btn').click(function () {
								var bikeID = $(this).data('stolenbikeid');
								var comments = $bikeTile.find('.comments-box').val();
								// Call the updateComments function to update comments for the bike
								updateComments(bikeID, comments);
							});

							// Event listener for the resolve investigation button
							$bikeTile.find('.resolve-investigation-btn').click(function () {
								var bikeID = $(this).data('stolenbikeid');
								// Call the resolveInvestigation function to mark the investigation as resolved
								resolveInvestigation(bikeID);
							});

							// Append the bike tile to the info container
							$('.info-container').append($bikeTile);
						});
					},
					error: function (xhr, status, error) {
						console.error('Error fetching stolen bikes:', error);
					}
				});
			}

			// Function to update comments for a stolen bike using Ajax and POST to updatecomments.php to update comments on investigation
			function updateComments(bikeID, comments) {
				$.ajax({
					url: '../php/updatecomments.php',
					type: 'POST',
					data: {
						stolenBikeID: bikeID,
						comments: comments
					},
					dataType: 'json',
					success: function (response) {
						console.log(response);
						// Handle success, if needed
					},
					error: function (xhr, status, error) {
						console.error('Error updating comments:', error);
					}
				});
			}

			// Function to resolve investigation for a stolen bike using ajax
			function resolveInvestigation(bikeID) {
				$.ajax({
					url: '../php/updateinvestigation.php',
					type: 'POST',
					data: {
						stolenBikeID: bikeID
					},
					dataType: 'json',
					success: function (response) {
						console.log(response);
						
					},
					error: function (xhr, status, error) {
						console.error('Error resolving investigation:', error);
					}
				});
			}
		// Google maps API
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9AUezX33t8xF3yyhBQu6tHfW0KeRhZig&callback=initMap"></script>
</body>

</html>
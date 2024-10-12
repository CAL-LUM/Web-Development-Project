<?php
// Start session
session_start();

// Check if user is logged in and is a police user
if (!isset($_SESSION['user_id']) || !isset($_SESSION['police_user'])) {
    header("Location: ../policesignin.html"); // Redirect to police sign-in page if not logged in or if public user
    exit(); // Stop further execution
}

// Retrieve user information from the database based on the session ID
$policeID = $_SESSION['user_id'];

// Establish connection to MySQL
$servername = "localhost:3306";
$username = "s4304413_Admin";
$password = "Nj2^3dz89";
$dbname = "s4304413_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Display error message if connection fails
}

// Fetch user data from the database based on user ID
$sql = "SELECT * FROM usersPolice WHERE policeID = ?";
$stmt = $conn->prepare($sql); // prepare SQL statement
$stmt->bind_param("i", $policeID); // Bind parameters 
$stmt->execute(); // execute statement
$result = $stmt->get_result(); // get results

if ($result->num_rows == 1) { // Check if there are rows in the result
    $police = $result->fetch_assoc(); // If successful, fetch user data
} else {
    echo "Error: User not found."; // Else, display error message if user not found
}

// Close database connection
$conn->close();
?>

<!-- Embed HTML in php file -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Viewport / Language basics -->
    <meta charset="UTF-8"> <!-- Set character encoding to UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define viewport settings for responsive design -->
    <meta http-equiv="X-UA-Compatible" content="IE-Edge"> <!-- Set compatibility mode for Internet Explorer -->
    <title>Police Update Details</title> <!-- Set the title of the webpage -->
    <link rel="stylesheet" href="../scss/profile.css"> <!-- Link css stylesheet -->

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"> <!-- Preconnect to Google Fonts API -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> <!-- Preconnect to Google Fonts API -->
    <link href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;800;900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> <!-- Link to Google Fonts stylesheet for custom fonts -->

</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navbar-container">
            <!-- Nav bar links-->
            <div class="menu">
                <a href="../index.html" class="menu-link">Home</a>
                <a href="../register.html" class="menu-link">Register a bike</a>
                <a href="../stolen.html" class="menu-link">Stolen bikes</a>
                <a href="../mybikes.html" class="menu-link">My bikes</a>
            </div>
            <!-- Additiona links for navbar-->
            <div class="menu">
                <a href="../signin.html" class="menu-link">Sign in</a>
                <a href="../signup.html" class="menu-btn">Sign up</a>
                <a href="../policesignin.html" class="menu-btn-police">Police</a>
            </div>
            <!-- Mini navbar for signing out and accessing profile -->
            <div class="mini-navbar">
                <a href="signout.php" class="mini-menu-btn">Sign out</a>
                <a href="policeprofile.php" class="mini-menu-btn">Profile</a>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="container">
        <div class="info-container">
            <h1>Police Account information</h1>
            <!-- Display Police user information -->
            <?php
            if (isset($police)) {
                echo "<p>Name: " . $police['firstName'] . " " . $police['lastName'] . "</p>"; // Display Police name
                echo "<p>Warrant Number: " . $police['warrantNumber'] . "</p>"; // Display Police warrant number
                echo "<p>Telephone: " . $police['telephone'] . "</p>"; // Display Police telephone
                echo "<p>Police Force: " . $police['policeForce'] . "</p>"; // Display police force
                echo "<p>Station Address: " . $police['stationAddressOne'] . ", " . $police['stationAddressTwo'] . ", " . $police['stationTown'] . ", " . $police['stationCounty'] . ", " . $police['stationPostcode'] . "</p>"; // Display Police station address
                echo "<p>Email: " . $police['email'] . "</p>"; // Display Police email
                // Add form to update information, post DATA to updatepoliceinfo.php for handling
                echo "<form class='update-form' action='updatepoliceinfo.php' method='post'>";
                echo "<input type='text' name='first_name' placeholder='First Name' value='" . $police['firstName'] . "'>"; 
                echo "<input type='text' name='last_name' placeholder='Last Name' value='" . $police['lastName'] . "'>"; 
                echo "<input type='text' name='warrant_number' placeholder='Warrant Number' value='" . $police['warrantNumber'] . "'>"; 
                echo "<input type='text' name='telephone' placeholder='Telephone' value='" . $police['telephone'] . "'>"; 
                echo "<input type='text' name='police_force' placeholder='Police Force' value='" . $police['policeForce'] . "'>"; 
                echo "<input type='text' name='station_address_one' placeholder='Station Address 1' value='" . $police['stationAddressOne'] . "'>"; 
                echo "<input type='text' name='station_address_two' placeholder='Station Address 2' value='" . $police['stationAddressTwo'] . "'>"; 
                echo "<input type='text' name='station_town' placeholder='Station Town' value='" . $police['stationTown'] . "'>"; 
                echo "<input type='text' name='station_county' placeholder='Station County' value='" . $police['stationCounty'] . "'>";
                echo "<input type='text' name='station_postcode' placeholder='Station Postcode' value='" . $police['stationPostcode'] . "'>"; 
                echo "<input type='email' name='email' placeholder='New Email' value='" . $police['email'] . "'>"; 
                echo "<input type='password' name='password' placeholder='New Password'>";
                echo "<input type='submit' value='Update Information'>"; // Submit button to update information
                echo "</form>";
            } else {
                echo "<p>User information not available.</p>"; // Display message if user information is not available
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <p class="footer-center">&copy; 2024 Bike Theft Prevention. All Rights Reserved.</p>
        </div>
    </footer>

    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- Scripts -->
    <script src="signout.js"></script>

</body>
</html>

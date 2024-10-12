<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html"); // Redirect to sign-in page if user is not logged in
    exit(); // Stop further execution
}

// Retrieve user information from the database based on the current session ID
$userID = $_SESSION['user_id'];

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

// Fetch user data from the database based on user ID of current session
$sql = "SELECT firstName, lastName, email FROM usersPublic WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc(); // Fetch user data
} else {
    echo "Error: User not found."; // Display error message if user is not found
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Viewport / Language basics -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE-Edge">
    <title>Profile</title> <!-- Title of the page -->
    <link rel="stylesheet" href="../scss/profile.css"> <!-- Link to the CSS stylesheet -->

    <!-- Google fonts and preconnections-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;800;900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>
<body>
	<!-- Navbar container -->
    <div class="navbar">
        <div class="navbar-container">
            <div class="menu">
				<!-- Links for navbar -->
                <a href="../index.html" class="menu-link">Home</a> 
                <a href="../register.html" class="menu-link">Register a bike</a>
                <a href="stolen.php" class="menu-link">Stolen bikes</a> 
                <a href="../mybikes.html" class="menu-link">My bikes</a>
            </div>
			<!-- additional links for navbar -->
            <div class="menu">
                <a href="../signin.html" class="menu-link">Sign in</a> 
                <a href="../signup.html" class="menu-btn">Sign up</a> 
                <a href="../policesignin.html" class="menu-btn-police">Police</a> 
            </div>
			<!-- Mini navbar to handle signing out and profile access -->
            <div class="mini-navbar">
                <a href="signout.php" class="mini-menu-btn">Sign out</a> 
                <a href="../profile.html" class="mini-menu-btn">Profile</a> 
            </div>
        </div>
    </div>

    <div class="container">
        <div class="info-container">
            <h1>Account information</h1>
            <!-- Display user information -->
            <?php
            if (isset($user)) { // Check if user data is available
                echo "<p>Name: " . $user['firstName'] . " " . $user['lastName'] . "</p>"; // Display user's first and last name
                echo "<p>Email: " . $user['email'] . "</p>"; // Display user's email
                // Add form to update email
                echo "<form class='update-form' action='updateemail.php' method='post'>";
                echo "<input type='email' name='new_email' placeholder='New Email'>"; // Input field for new email
                echo "<input type='submit' value='Update Email'>"; // Submit button for updating email
                echo "</form>";
                // Add form to update password
                echo "<form class='update-form' action='updatepassword.php' method='post'>";
                echo "<input type='password' name='current_password' placeholder='Current Password'>"; // Input field for current password
                echo "<input type='password' name='new_password' placeholder='New Password'>"; // Input field for new password
                echo "<input type='password' name='confirm_password' placeholder='Confirm New Password'>"; // Input field for confirming new password
                echo "<input type='submit' value='Update Password'>"; // Submit button for updating password
                echo "</form>";
            } else {
                echo "<p>User information not available.</p>"; // Display message if user information is not available
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <p class="footer-center">&copy; 2024 Bike Theft Prevention. All Rights Reserved.</p>
        </div>
    </footer>

    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- Scripts -->
    <script src="../js/signout.js"></script>

</body>
</html>
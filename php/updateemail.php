<?php
 // start / resume session
session_start();

// Check if user is logged in, if not redirect to signin.html
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from current session
    $userID = $_SESSION['user_id'];
    
    // Retrieve new email from form
    $newEmail = $_POST['new_email'];
    
    // Email validation
    $emailRegex = "/^[^\s@]+@[^\s@]+\.(com|co\.uk|uk)$/";
    if (!preg_match($emailRegex, $newEmail)) {
        echo "Invalid email format";
        exit();
    }
    
    // Establish connection to MySQL
    $servername = "localhost:3306";
    $username = "s4304413_Admin";
    $password = "Nj2^3dz89";
    $dbname = "s4304413_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update user's email in the database
    $sql = "UPDATE usersPublic SET email = ? WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newEmail, $userID);
    if ($stmt->execute()) {
        // Email updated successfully, redirect to profile page
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating email: " . $conn->error;
    }

    // Close database connection
    $conn->close();
} else {
    echo "Invalid request";
}
?>
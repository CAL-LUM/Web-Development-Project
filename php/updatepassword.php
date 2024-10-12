<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from current session
    $userID = $_SESSION['user_id'];
    
    // Retrieve form data
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Password regex/validation
    $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/";
    if (!preg_match($passwordRegex, $newPassword)) {
        echo "Password must be between 6 to 20 characters which contain at least one numeric digit, one uppercase, and one lowercase letter.";
        exit();
    }

    // Check if new password matches the confirmed password
    if ($newPassword !== $confirmPassword) {
        echo "New password and confirm password do not match"; // error if passwords do not match
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

    // Fetch user's current password from the database
    $sql = "SELECT password FROM usersPublic WHERE userID = ?";
	// Prepare and bind SQL statement and fetch result
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    // Verify if the current password matches the one stored in the database
    if (password_verify($currentPassword, $hashedPassword)) {
        // Hash the new password before updating
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update user's password in the database
        $sql = "UPDATE usersPublic SET password = ? WHERE userID = ?";
		// Prepare and bind statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashedNewPassword, $userID);
        if ($stmt->execute()) {
            // Password updated successfully, redirect to profile page
            header("Location: profile.php");
            exit();
        } else {
            echo "Error updating password: " . $conn->error; // throw connection error 
        }
    } else {
        echo "Current password is incorrect"; // throw incorrect password error
    }

    // Close database connection
    $conn->close();
} else {
    echo "Invalid request";
}
?>
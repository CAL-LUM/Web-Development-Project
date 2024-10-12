<?php
// Start session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish connection to MySQL catabase
    $servername = "localhost:3306"; // server name 
    $username = "s4304413_Admin"; // username 
    $password = "Nj2^3dz89"; // password 
    $dbname = "s4304413_db"; // database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Display error message if connection fails
    }

    // Retrieve form data through POST data type
    $email = $_POST['email']; 
    $password = $_POST['password']; 

    // SQL query to fetch police user data
    $sql = "SELECT * FROM usersPolice WHERE email = ?";
    $stmt = $conn->prepare($sql); // Prepare SQL query
    $stmt->bind_param("s", $email); // Bind parameters
    $stmt->execute(); // Execute SQL query
    $result = $stmt->get_result(); // Get result from query

    if ($result->num_rows == 1) { // If user found, verify password and fetch user data
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) { 
            $_SESSION['user_id'] = $row['policeID']; // Set session user ID
            $_SESSION['police_user'] = true; // Set session for police user
            header("Location: ../policedashboard.html"); // Redirect to police dashboard page
            exit(); // Stop further execution
        } else {
            // Invalid password
            $error_message = "Invalid email or password."; // error message
        }
    } else {
        // User not found
        $error_message = "User not found."; // error message
    }

    // Close connection
    $conn->close(); // Close database connection
}
?>
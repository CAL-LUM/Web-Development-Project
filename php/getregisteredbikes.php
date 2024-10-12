<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html"); // Redirect to signin.html if user is not logged in
    exit();
}

// Establish connection to MySQL database
$servername = "localhost:3306"; // Server name
$username = "s4304413_Admin"; // Username
$password = "Nj2^3dz89"; // Password
$dbname = "s4304413_db"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Display error message if connection fails
}

// Get the user ID of the currently logged in user
$userID = $_SESSION['user_id'];

// SQL select query to fetch registered bikes for the user
$sql = "SELECT * FROM bikesPublic WHERE userID = '$userID'";

$result = $conn->query($sql); // Execute the SQL query

// Store results in an array
$bikes = array();
if ($result->num_rows > 0) { // Check if there are rows in the result
    while ($row = $result->fetch_assoc()) { // Loop through each row of the result
        // Append base URL to image path
        $row['imagePath'] = "/uploads/" . $row['imagePath']; // Updated line to include base URL
        $bikes[] = $row; // Add the row to the bikes array
    }
}

// Close connection
$conn->close();

// Return JSON data
echo json_encode($bikes); // Convert the bikes array to JSON format and echo it
?>
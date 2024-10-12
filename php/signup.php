<?php
// Establish connection to MySQL database 
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

// Retrieve form data from POST data type
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['emailUser'];
$password = $_POST['passwordUser'];

// PHP validation for first and last name, only alphabetical characters and less than 20 characters total.
if (strlen($firstName) > 20 || !ctype_alpha($firstName)) {
    die("Invalid first name.");
}

if (strlen($lastName) > 20 || !ctype_alpha($lastName)) {
    die("Invalid last name.");
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare and bind parameters for SQL statement
$stmt = $conn->prepare("INSERT INTO usersPublic (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
if (!$stmt) {
    die("Error binding parameters: " . $stmt->error); // throw error if unsuccessful 
}

// Execute query
if ($stmt->execute()) {
    // Redirect to signin page
    header("Location: ../signin.html");
    exit();
} else {
    echo "Error executing query: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
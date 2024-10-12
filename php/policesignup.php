<?php
// Establish connection to MySQL database
$servername = "localhost:3306"; // servername
$username = "s4304413_Admin"; // username
$password = "Nj2^3dz89"; // password
$dbname = "s4304413_db"; // database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Display error message if connection fails
}

// Retrieve form data using the POST data type from form
$firstName = $_POST['firstNamePolice']; 
$lastName = $_POST['lastNamePolice']; 
$warrantNumber = $_POST['warrantNumber']; 
$telephone = $_POST['telephone']; 
$policeForce = $_POST['policeForce']; 
$stationAddressOne = $_POST['stationAddressOne']; 
$stationAddressTwo = isset($_POST['stationAddressTwo']) ? $_POST['stationAddressTwo'] : '';  provided
$stationTown = $_POST['stationTown']; 
$stationCounty = $_POST['stationCounty'];
$stationPostcode = $_POST['stationPostcode'];
$email = $_POST['emailPolice']; 
$password = $_POST['passwordPolice'];

// Additional PHP validation to check validity of POST inputs, and display error message if invalid.
if (strlen($firstName) > 20 || !ctype_alpha($firstName)) { 
    die("Invalid first name."); 
}

if (strlen($lastName) > 20 || !ctype_alpha($lastName)) { 
    die("Invalid last name."); 
}

if (!ctype_digit($warrantNumber) || strlen($warrantNumber) < 4 || strlen($warrantNumber) > 8) { 
    die("Invalid warrant number."); 
}

if (!preg_match("/^[0-9]{1,11}$/", $telephone)) {
    die("Invalid telephone number."); 
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO usersPolice (policeID, firstName, lastName, warrantNumber, telephone, policeForce, stationAddressOne, stationAddressTwo, stationTown, stationCounty, stationPostcode, email, password) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error); // Display error message if preparing statement fails
}

// Bind parameters, s for string, i for integer.
$stmt->bind_param("ssisssssssss", $firstName, $lastName, $warrantNumber, $telephone, $policeForce, $stationAddressOne, $stationAddressTwo, $stationTown, $stationCounty, $stationPostcode, $email, $password);
if (!$stmt) {
    die("Error binding parameters: " . $stmt->error); // Display error message if binding parameters fails
}

// Execute query if sucessful and display success mesage, otherwise display eror message
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error executing query: " . $stmt->error; 
}

// Close statement and connection
$stmt->close();
$conn->close(); 
?>
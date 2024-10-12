<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

// Establish connection to MySQL
$servername = "localhost:3306"; // servername
$username = "s4304413_Admin"; // username 
$password = "Nj2^3dz89"; // passwrod
$dbname = "s4304413_db"; // database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data through POST datatype from form on register bike page.
$mpn = $_POST['mpn']; 
$brand = $_POST['brand']; 
$model = $_POST['model']; 
$type = $_POST['type']; 
$wheelSize = $_POST['wheel-size']; 
$colour = $_POST['colour']; 
$gears = $_POST['gears']; 
$brakeType = $_POST['brake-type']; 
$suspension = $_POST['suspension']; 
$gender = $_POST['gender']; 
$ageGroup = $_POST['age-group']; 

// Check if image data is received and handle errors
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo "Error: Image upload failed. Error code: " . $_FILES['image']['error'];
    exit();
}

// Define target directory to store the uploaded image
$targetDir = "../uploads/";

// Get the user ID of the currently logged-in user
$userID = $_SESSION['user_id'];

// Define target filename with a unique identifier through uniqid
$targetFilename = $userID . "-" . uniqid() . "-image"; 

// Append file extension to the target filename
$targetFilePath = $targetDir . $targetFilename . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

// Move uploaded file to target directory with the new filename
if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
    echo "Error: Unable to move uploaded file.";
    exit();
}

// Set imagePath to the file path with the new filename
$imagePath = $targetFilePath;

// Prepare SQL statement with placeholders
$sql = "INSERT INTO bikesPublic (userID, mpn, brand, model, type, wheelSize, colour, gears, brakeType, suspension, gender, ageGroup, imagePath) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare and bind the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssssssssss", $userID, $mpn, $brand, $model, $type, $wheelSize, $colour, $gears, $brakeType, $suspension, $gender, $ageGroup, $imagePath);

// Bind parameters and execute statement
if ($stmt->execute()) {
    // Redirect to the index.html page
    header("Location: ../index.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error; // echo errors
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
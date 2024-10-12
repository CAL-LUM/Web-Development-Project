<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: policesignin.html");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from session
    $policeID = $_SESSION['user_id'];

    // Retrieve updated information from form on policeprofile.php
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $warrantNumber = $_POST['warrant_number'];
    $telephone = $_POST['telephone'];
    $policeForce = $_POST['police_force'];
    $stationAddressOne = $_POST['station_address_one'];
    $stationAddressTwo = $_POST['station_address_two'];
    $stationTown = $_POST['station_town'];
    $stationCounty = $_POST['station_county'];
    $stationPostcode = $_POST['station_postcode'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs
    $nameRegex = "/^[a-zA-Z]{1,20}$/"; // Up to 20 characters, alphabetical only
    $warrantNumberRegex = "/^\d{4,8}$/"; // 4 to 8 digits
    $telephoneRegex = "/^\d{1,11}$/"; // Up to 11 digits
    $policeForceRegex = "/^[a-zA-Z0-9 ]{1,255}$/"; // Up to 255 characters, letters and numbers
    $addressRegex = "/^[a-zA-Z0-9 ]{1,255}$/"; // Up to 255 characters, letters and numbers
    $emailRegex = "/^[^\s@]+@[^\s@]+\.(police\.uk|police\.gov\.uk|police\.gov)$/"; // IMplement valid email format
    $passwordRegex = "/^.{6,200}$/"; // At least 6 characters, up to 200 characters

	// Perform regular expression matching for all input fields
    if (!preg_match($nameRegex, $firstName)) {
        echo "Invalid first name format";
        exit();
    }
    if (!preg_match($nameRegex, $lastName)) {
        echo "Invalid last name format";
        exit();
    }
    if (!preg_match($warrantNumberRegex, $warrantNumber)) {
        echo "Invalid warrant number format";
        exit();
    }
    if (!preg_match($telephoneRegex, $telephone)) {
        echo "Invalid telephone number format";
        exit();
    }
    if (!preg_match($policeForceRegex, $policeForce)) {
        echo "Invalid police force format";
        exit();
    }
    if (!preg_match($addressRegex, $stationAddressOne)) {
        echo "Invalid station address format";
        exit();
    }
    if (!preg_match($addressRegex, $stationAddressTwo)) {
        echo "Invalid station address format";
        exit();
    }
    if (!preg_match($addressRegex, $stationTown)) {
        echo "Invalid station town format";
        exit();
    }
    if (!preg_match($addressRegex, $stationCounty)) {
        echo "Invalid station county format";
        exit();
    }
    if (!preg_match($addressRegex, $stationPostcode)) {
        echo "Invalid station postcode format";
        exit();
    }
    if (!preg_match($emailRegex, $email)) {
        echo "Invalid email format";
        exit();
    }
    if (!preg_match($passwordRegex, $password)) {
        echo "Invalid password format";
        exit();
    }

    // Establish connection to MySQL
    $servername = "localhost:3306";
    $username = "s4304413_Admin";
    $dbpassword = "Nj2^3dz89";
    $dbname = "s4304413_db";

    // Create connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update user's information in the database
    $sql = "UPDATE usersPolice SET firstName=?, lastName=?, warrantNumber=?, telephone=?, policeForce=?, stationAddressOne=?, stationAddressTwo=?, stationTown=?, stationCounty=?, stationPostcode=?, email=?, password=? WHERE policeID=?";
	// Prepare SQL statement and bind it
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssi", $firstName, $lastName, $warrantNumber, $telephone, $policeForce, $stationAddressOne, $stationAddressTwo, $stationTown, $stationCounty, $stationPostcode, $email, $hashedPassword, $policeID);
    if ($stmt->execute()) {
        // Information updated successfully, redirect to profile page
        header("Location: policeprofile.php");
        exit();
    } else {
        echo "Error updating information: " . $conn->error; // throw connection error
    }

    // Close database connection
    $conn->close();
} else {
    echo "Invalid request";
}
?>
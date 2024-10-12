<?php
session_start();

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

// Retrieve form data through POST data type
$mpn = $_POST['MPN'];
$Address1 = $_POST['Address1'];
$Address2 = $_POST['Address2'];
$postcode = $_POST['postcode'];

// Parse and format the date
$date_str = $_POST['date'];
$date = DateTime::createFromFormat('Y/m/d', $date_str);

// Check if the entered date matches the format Y-M-D
if ($date !== false && $date->format('Y/m/d') === $date_str) {
    $formatted_date = $date->format('Y-m-d');

    // Get the user ID of the currently signed-in user
    $userID = $_SESSION['user_id'];

    // SQL query to fetch registered bikes for the user
    $stmt = $conn->prepare("SELECT bikeID FROM bikesPublic WHERE userID = ? AND mpn = ?");
	// Bind parameters for SQL query
    $stmt->bind_param("is", $userID, $mpn);
	// Execute statement
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If Bike exists in the database, proceed to insert into stolenBikes
        $stmt->bind_result($bikeID);
        $stmt->fetch();
        // Prepare statement
        $stmt = $conn->prepare("INSERT INTO stolenBikes (userID, bikeID, Address1, Address2, postcode, date) VALUES (?, ?, ?, ?, ?, ?)");
		// Bind parameters
        $stmt->bind_param("iissss", $userID, $bikeID, $Address1, $Address2, $postcode, $formatted_date);
        
		if ($stmt->execute()) {
            // Insertion successful
			header("Location: ../index.html"); // redirect to index.html (home page)
        } else {
            echo "Error executing query: " . $stmt->error;
        }
		
    } else {
        echo "Bike not found in your registered bikes."; // error for bike not being found 
    }
} else {
    echo "Invalid date format. Please write date in 'YYYY/MM/DD'."; // error for date format
}

// Close statement and connection
if(isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
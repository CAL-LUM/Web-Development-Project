<?php
session_start();

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

// Extract date filter parameters from GET request
$start_date = $_GET['start_date'] ?? ''; // Get start_date parameter from GET request, if not set, default to an empty string
$end_date = $_GET['end_date'] ?? ''; // Get end_date parameter from GET request, if not set, default to an empty string

// Prepare the SQL query with the date range filter
$sql = "SELECT stolenBikes.stolenBikeID, stolenBikes.Address1, stolenBikes.Address2, stolenBikes.postcode, stolenBikes.comments, 
        usersPublic.firstName, usersPublic.lastName, usersPublic.email, 
        bikesPublic.mpn, bikesPublic.brand, bikesPublic.model, bikesPublic.type, bikesPublic.wheelSize, 
        bikesPublic.colour, bikesPublic.gears, bikesPublic.brakeType, bikesPublic.suspension, bikesPublic.gender, bikesPublic.ageGroup,
        bikesPublic.imagePath 
        FROM stolenBikes 
        JOIN usersPublic ON stolenBikes.userID = usersPublic.userID 
        JOIN bikesPublic ON stolenBikes.bikeID = bikesPublic.bikeID";

if (!empty($start_date) && !empty($end_date)) { // Check if both start_date and end_date are provided
    // Format dates to match database format of Y-M-D.
    $start_date_formatted = date('Y-m-d', strtotime($start_date)); // Convert start_date to database format
    $end_date_formatted = date('Y-m-d', strtotime($end_date)); // Convert end_date to database format
    
    // Add WHERE clause to filter by date range
    $sql .= " WHERE stolenBikes.date BETWEEN '$start_date_formatted' AND '$end_date_formatted'";
} elseif (empty($start_date) && !empty($end_date)) { // Check if only end_date is provided
    // If only end date is specified, filter bikes stolen before end date
    $end_date_formatted = date('Y-m-d', strtotime($end_date)); // Convert end_date to database format
    $sql .= " WHERE stolenBikes.date <= '$end_date_formatted'";
} elseif (!empty($start_date) && empty($end_date)) { // Check if only start_date is provided
    // If only start date is specified, filter bikes stolen after start date
    $start_date_formatted = date('Y-m-d', strtotime($start_date)); // Convert start_date to database format
    $sql .= " WHERE stolenBikes.date >= '$start_date_formatted'";
}

// Execute the query
$result = $conn->query($sql);

if ($result) { // Check if query was successful
    // Fetch data and encode as JSON
    $rows = array(); // Initialize an empty array to store fetched rows
    while ($row = $result->fetch_assoc()) { // Loop through each fetched row
        $rows[] = $row; // Append the row to the $rows array
    }
    echo json_encode($rows); // Encode $rows array as JSON and echo it
} else {
    // Handle query error
    echo json_encode(array('error' => "Error fetching stolen bikes: " . $conn->error)); // Encode error message as JSON and echo it
}

// Close connection
$conn->close(); // Close the database connection
?>
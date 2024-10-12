<?php
session_start(); // Start the session

// Check if the request method is POST and if the stolenBikeID is set in the POST data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["stolenBikeID"])) {
    $stolenBikeID = $_POST["stolenBikeID"]; // Retrieve the stolenBikeID 

    // Establish connection to MySQL
    $servername = "localhost:3306";
    $username = "s4304413_Admin";
    $password = "Nj2^3dz89";
    $dbname = "s4304413_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection to the database was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Terminate and display error message if connection fails
    }

    // SQL query to delete the stolen bike with the given stolenBikeID
    $sql = "DELETE FROM stolenBikes WHERE stolenBikeID = $stolenBikeID";

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // If the query was successful, return a JSON response indicating success
        echo json_encode(array("success" => "Investigation resolved successfully"));
    } else {
        // If there was an error executing the query, return a JSON response with the error message
        echo json_encode(array("error" => "Error resolving investigation: " . $conn->error));
    }

    // Close the database connection
    $conn->close();
} else {
    // If the request method is not POST or stolenBikeID is not set, return a JSON response indicating an invalid request
    echo json_encode(array("error" => "Invalid request"));
}
?>
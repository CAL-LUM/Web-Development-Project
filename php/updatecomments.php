<?php
session_start();


// Check if the request method is POST and required parameters are set
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["stolenBikeID"]) && isset($_POST["comments"])) {
    $comments = $_POST["comments"];
    $stolenBikeID = $_POST["stolenBikeID"];

    // Establish connection to MySQL
    $servername = "localhost:3306";
    $username = "s4304413_Admin";
    $password = "Nj2^3dz89";
    $dbname = "s4304413_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $sql = "UPDATE stolenBikes SET comments = ? WHERE stolenBikeID = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $comments, $stolenBikeID);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(array("success" => "Comments updated successfully"));
    } else {
        echo json_encode(array("error" => "Error updating comments: " . $conn->error));
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array("error" => "Invalid request"));
}
?>
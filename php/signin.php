<?php
// Establish connection to MySQL database
$servername = "localhost:3306"; // Server name
$username = "s4304413_Admin"; // Username
$password = "Nj2^3dz89"; // Password 
$dbname = "s4304413_db"; // Name of the database

// Create connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection fails, terminate and display error message
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data from the POST request
$email = $_POST['email']; 
$password = $_POST['password']; 

// Fetch user data from the database based on the provided email
$sql = "SELECT userID, email, password FROM usersPublic WHERE email = ?";
$stmt = $conn->prepare($sql); // Prepare SQL statement 
$stmt->bind_param("s", $email); // Bind parameter 
$stmt->execute(); // Execute statement
$result = $stmt->get_result(); // Get the result set from the executed statement

// Check if a user with the provided email exists in the database, if found, fetch data for user
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc(); 
    $hashedPassword = $row['password']; // Retrieve the hashed password stored in the database
    
    // Verify password matches hashed password
    if (password_verify($password, $hashedPassword)) {
        //start a session and set the user's ID in the session variable
        session_start(); // Start / resume session
        $_SESSION['user_id'] = $row['userID']; // Set the user's ID in the session variable
        
        // Output the user's ID to the browser console for debugging purposes 
        echo '<script>console.log("User ID: ' . $_SESSION['user_id'] . '");</script>';
        
        // Display an alert message to notify the user of successful login after a delay
        echo '<script>
               setTimeout(function(){
                  alert("User logged in");
                  window.location.href = "../index.html"; // Redirect the user to the index.html page
              }, 1000); // Delay the alert message by 1 second
            </script>';
        
        exit(); // Terminate script execution after successful login
    } else {
        // If the provided password does not match the hashed password, set an error message
        $error_message = "Invalid email or password."; 
    }
} else {
    // If no user is found with the provided email, set an error message
    $error_message = "User not found.";
}
?>

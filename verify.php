<?php
require 'vendor/autoload.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message
$message = "";

// Handle email verification
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token
    $sql = "SELECT * FROM users WHERE token = ? AND status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, update the user's status to 'verified'
        $sql = "UPDATE users SET status = 'verified', token = NULL WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        
        if ($stmt->execute()) {
            // Ensure the update actually happened
            if ($stmt->affected_rows > 0) {
                $message = "Your email has been verified! You can now <a href='welcome.php'>login</a>.";
            } else {
                $message = "Verification failed. No rows updated.";
            }
        } else {
            $message = "Error updating record: " . $stmt->error;
        }
    } else {
        $message = "Invalid or expired token.";
    }

    $stmt->close();
} else {
    $message = "No token provided.";
}

$conn->close();

// Display message
echo $message;
?>

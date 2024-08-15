<?php
// Database connection parameters
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'travel_management_system';

// Create a connection
$mysqli = new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM userdetails WHERE User_Email = ?";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, send reset password email
            $resetToken = bin2hex(random_bytes(16));
            $resetUrl = "http://localhost/tourismwebsiteaqsa/forgotpassord.php?token=$resetToken";
            
            // Update the user record with the reset token (optional)
            $updateSql = "UPDATE userdetails SET reset_token = ? WHERE User_Email = ?";
            $updateStmt = $mysqli->prepare($updateSql);
            if ($updateStmt) {
                $updateStmt->bind_param("ss", $resetToken, $email);
                $updateStmt->execute();
                $updateStmt->close();
            }

            // Send email
            $subject = "Password Reset Request";
            $message = "Click the following link to reset your password: $resetUrl";
            $headers = "From: no-reply@tourismwebsiteaqsa.com\r\n";
            if (mail($email, $subject, $message, $headers)) {
                echo "An email has been sent to $email with instructions to reset your password.";
            } else {
                echo "Failed to send email.";
            }
        } else {
            echo "Email does not exist.";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
}

// Close the connection
$mysqli->close();
?>

<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'travel_management_system';

// Create a new MySQLi instance
$mysqli = new mysqli($host, $user, $password, $database);

// Check the connection
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$email = $_POST["email"];
$userPassword = $_POST["password"];

// Prepare the SQL query
$sql = "SELECT * FROM userdetails WHERE User_Email = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    // Bind the parameters with the values
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Check if the query executed successfully
    if ($result) {
        if ($result->num_rows > 0) {
            // Query executed successfully, fetch and display the data
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($userPassword, $row["UserPassword"])) {
                session_start();
                $_SESSION['user_email'] = $email;
                header("Location: booking.html");
                exit();
            } else {
                echo "Invalid password";
            }
        } else {
            echo "No records were found";
        }
    } else {
        // Query execution failed
        echo "Error executing the query: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Failed to prepare the SQL statement: " . $mysqli->error;
}

// Close the MySQLi connection
$mysqli->close();
?>

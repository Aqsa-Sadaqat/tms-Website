<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$database = "travel_management_system"; 

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it does not exist
$db_check = $conn->query("SHOW DATABASES LIKE '$database'");
if ($db_check->num_rows == 0) {
    $create_db = $conn->query("CREATE DATABASE $database");
    if (!$create_db) {
        die('Error creating database: ' . $conn->error);
    }
}

// Select the database
$conn->select_db($database);

// Create table if it does not exist
$create_table_sql = "
CREATE TABLE IF NOT EXISTS userdetails (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(255) NOT NULL,
    User_Email VARCHAR(255) NOT NULL,
    UserPassword VARCHAR(255) NOT NULL
)";
if (!$conn->query($create_table_sql)) {
    die('Error creating table: ' . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // $stmt = $conn->prepare("INSERT INTO userdetails (UserName, User_Email, UserPassword, PhoneNumber, FamilyMembers, Destination, CarOptions) VALUES (?, ?, ?, ?, ?, ?, ?)");
       // $phoneNumber = $_POST['phoneNumber'];
    // $familyMembers = $_POST['familyMembers'];
    // $destination = $_POST['destination'];
    // $carOptions = $_POST['carOptions'];
    $stmt = $conn->prepare("INSERT INTO userdetails (UserName, User_Email, UserPassword) VALUES (?, ?, ?)");
    $username = $firstname . ' ' . $lastname;
    
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "done signUP";
        header("Location: login.html");
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); 
}

$conn->close(); 
?>

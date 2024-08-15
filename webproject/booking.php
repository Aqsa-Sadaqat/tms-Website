<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'travel_management_system';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if (!isset($_SESSION['user_email'])) {
    die('User email is not set in the session. <a href="index.html">Go back</a>');
}

$email = $_SESSION['user_email'];
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';
$familyMembers = $_POST['familyMembers'] ?? 0;
$destination = $_POST['destination'] ?? '';
$carOptions = $_POST['carOptions'] ?? '';


$create_table_sql = "CREATE TABLE IF NOT EXISTS registerDetails (
        UserID INT AUTO_INCREMENT PRIMARY KEY,
        UserName VARCHAR(255) NOT NULL,
        UserEmail VARCHAR(255) NOT NULL,
        PhoneNumber VARCHAR(20) NOT NULL,
        FamilyMembers INT NOT NULL,
        Destination VARCHAR(255) NOT NULL,
        CarOptions VARCHAR(255) NOT NULL
    )";
if (!$mysqli->query($create_table_sql)) {
    die('Error creating table: ' . $mysqli->error);
}


$sql = "INSERT INTO registerDetails (UserName, UserEmail, PhoneNumber, FamilyMembers, Destination, CarOptions) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $userName = $firstName . ' ' . $lastName;
    $stmt->bind_param("sssiss", $userName, $email, $phoneNumber, $familyMembers, $destination, $carOptions);

    if ($stmt->execute()) {
        echo 'Registration successful!';
    } else {
        echo 'Error executing statement: ' . $stmt->error;
    }

    $stmt->close();
} else {
    echo 'Error preparing statement: ' . $mysqli->error;
}

$mysqli->close();

?>
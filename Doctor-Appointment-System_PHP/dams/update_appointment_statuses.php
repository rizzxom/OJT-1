<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "damsdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Calculate the time 1 hour ago
$oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));

// Update appointment statuses where appointment time is older than 1 hour
$sql = "UPDATE tbldoctor SET AppointmentStatus = 0 WHERE AppointmentTime < '$oneHourAgo'";
if ($conn->query($sql) === TRUE) {
    echo "Appointment statuses updated successfully!";
} else {
    echo "Error updating appointment statuses: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

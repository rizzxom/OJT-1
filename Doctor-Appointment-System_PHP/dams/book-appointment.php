<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "damsmsdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$date = $_POST['date'];
$time = $_POST['time'];
$doctor_id = $_POST['doctor_id']; // Assuming doctor_id is obtained from the form

// Check if the selected date and time with the doctor are available
$sql_check_availability = "SELECT * FROM appointments WHERE date='$date' AND time='$time' AND doctor_id='$doctor_id'";
$result_check_availability = $conn->query($sql_check_availability);

if ($result_check_availability->num_rows > 0) {
    // Date and time slot with the doctor is already booked
    echo "Sorry, the selected date and time slot with the doctor are already booked. Please choose another time.";
} else {
    // Date and time slot with the doctor is available, insert the appointment into the database
    $sql_insert_appointment = "INSERT INTO appointments (name, email, phone, date, time, doctor_id)
                               VALUES ('$name', '$email', '$phone', '$date', '$time', '$doctor_id')";

    if ($conn->query($sql_insert_appointment) === TRUE) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . $sql_insert_appointment . "<br>" . $conn->error;
    }
}

$conn->close();
?>

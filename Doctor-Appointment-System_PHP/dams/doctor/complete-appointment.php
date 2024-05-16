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

// Check if editid parameter is provided
if(isset($_GET['editid'])) {
    // Get the appointment ID from the URL parameter
    $appointmentId = $_GET['editid'];
    
    // Retrieve the doctor ID associated with the appointment from tblappointment table
    $sql = "SELECT Doctor FROM tblappointment WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $appointmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the appointment exists and doctor ID is retrieved
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $doctorId = $row['Doctor'];
        
        // Check if the appointment is already completed (status = 0)
        $sqlCheck = "SELECT AppointmentStatus FROM tbldoctor WHERE ID = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param('i', $doctorId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $rowCheck = $resultCheck->fetch_assoc();
        $appointmentStatus = $rowCheck['AppointmentStatus'];
        
        if($appointmentStatus == 0) {
            // If appointment status is already 0, display a message and exit
            echo "<script>alert('Appointment already completed!'); window.location.href='approved-appointment.php';</script>";
            exit; // Exit to prevent further execution of the script
        } else {
            // Update the appointment status for the specified doctor
            $sqlUpdate = "UPDATE tbldoctor SET AppointmentStatus = 0 WHERE ID = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param('i', $doctorId);
            $stmtUpdate->execute();
            
            // Check if the update was successful
            if($stmtUpdate->affected_rows > 0) {
                // Success message displayed using JavaScript with redirect
                echo "<script>alert('Appointment status updated successfully!'); window.location.href='approved-appointment.php';</script>";
                exit; // Exit to prevent further execution of the script
            } else {
                echo "Failed to update appointment status.";
            }
        }
    } else {
        echo "Doctor ID not found for the specified appointment";
    }
} else {
    // Redirect or display an error message if editid parameter is not provided
    echo "Appointment ID not specified";
}

// Close the database connection
$conn->close();
?>

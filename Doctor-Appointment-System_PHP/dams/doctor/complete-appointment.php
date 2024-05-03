<?php
// Check if editid parameter is provided
if (isset($dbh)){
if(isset($_GET['editid'])) {
    // Get the appointment ID from the URL parameter
    $appointmentId = $_GET['editid'];
    
    // Retrieve the doctor ID associated with the appointment from tblappointment table
    $sql = "SELECT Doctor FROM tblappointment WHERE ID = :appointmentId";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':appointmentId', $appointmentId, PDO::PARAM_INT);
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if the appointment exists and doctor ID is retrieved
    if($appointment && isset($appointment['Doctor'])) {
        $doctorId = $appointment['Doctor'];
        
        // Update the appointment status for the specified doctor
        $sqlUpdate = "UPDATE tbldoctor SET AppointmentStatus = 0 WHERE ID = :doctorId";
        $stmtUpdate = $dbh->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
        $stmtUpdate->execute();
        
        // Check if the update was successful
        if($stmtUpdate->rowCount() > 0) {
            echo '<script>alert("Appointment status updated successfully for doctor ID: ' . $doctorId . '")</script>';
        } else {
            echo '<script>alert("Failed to update appointment status for doctor ID: ' . $doctorId . '")</script>';
        }
    } else {
        echo '<script>alert("Doctor ID not found for the specified appointment")</script>';
    }
} else {
    // Redirect or display an error message if editid parameter is not provided
    echo '<script>alert("Appointment ID not specified")</script>';
}
}
?>

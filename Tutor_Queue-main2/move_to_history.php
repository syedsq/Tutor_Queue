<?php
// Include database connection
require 'db_connect.php';

// Get the student data from the POST request
$studentId = $_POST['studentId'];
$studentName = $_POST['studentName'];

// Fetch student data from the students table
$query = "SELECT * FROM requests WHERE student_id = :studentId";
$stmt = $conn->prepare($query);
$stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
$stmt->execute();
$studentData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($studentData) {
    // Insert into tutor_sessions (history table)
    $insertQuery = "INSERT INTO tutor_sessions (student_name, student_id, subject, session_type, session_duration)
                    VALUES (:student_name, :student_id, :subject, :session_type, 0)"; // Set session_duration to 0 for now
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bindParam(':student_name', $studentData['student_name']);
    $insertStmt->bindParam(':student_id', $studentData['student_id']);
    $insertStmt->bindParam(':subject', $studentData['topic']);
    $insertStmt->bindParam(':session_type', $studentData['session_type']);
    $insertStmt->execute();

    // Delete student from the students table
    //TODO: make this delete request with request id not student id since a student could have entered multiple requests
    $deleteQuery = "DELETE FROM requests WHERE student_id = :studentId";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
    $deleteStmt->execute();

    echo "Success";
} else {
    echo "Error: Student not found";
}
?>

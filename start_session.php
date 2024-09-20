<?php
// start_session.php - Starts a session for a student

require 'db_connect.php';

// Check if a student_id is passed
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Fetch the student details
    $query = "SELECT * FROM students WHERE id = :student_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // Remove the student from the queue (for now, we can just delete them or flag them as "in session")
        $deleteQuery = "DELETE FROM students WHERE id = :student_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':student_id', $student_id);
        $deleteStmt->execute();

        // Redirect to the session tracking page with the student's details
        header("Location: track_session.php?student_id=$student_id&full_name={$student['full_name']}");
        exit();
    } else {
        echo "Student not found.";
    }
} else {
    echo "No student selected.";
}

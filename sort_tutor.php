<?php
// Include database connection
require 'db_connect.php';

// Sorting algorithm function to get available tutor
function getAvailableTutor($class, $time) {
    global $conn;

    // Query the database for tutors available for the selected class and time
    $query = "SELECT tutors.id, tutors.tutor_name, tutor_availability.day_of_week, 
              tutor_availability.start_time, tutor_availability.end_time
              FROM tutors 
              INNER JOIN tutor_availability ON tutors.id = tutor_availability.tutor_id
              WHERE tutors.subject LIKE :class
              AND :time BETWEEN tutor_availability.start_time AND tutor_availability.end_time";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':class', $class);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Fetch the results
    $tutors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are any tutors available
    if (count($tutors) > 0) {
        // Return the first available tutor for simplicity
        return $tutors[0];
    } else {
        // No tutors available
        return null;
    }
}
?>

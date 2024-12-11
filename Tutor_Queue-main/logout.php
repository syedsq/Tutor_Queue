<?php
session_start();
require 'db_connect.php';

if (isset($_SESSION['tutor_id'])) {
    $tutor_id = $_SESSION['tutor_id'];

    // Set tutor status as "logged out"
    $updateStatusQuery = "UPDATE tutors SET is_logged_in = 0 WHERE id = :tutor_id";
    $updateStmt = $conn->prepare($updateStatusQuery);
    $updateStmt->bindParam(':tutor_id', $tutor_id);
    $updateStmt->execute();
}

// Destroy session and redirect
session_destroy();
header('Location: tutor_login.php');
?>

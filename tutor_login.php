<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $banner_id = $_POST['banner_id'];

    $query = "SELECT * FROM tutors WHERE email = :email AND banner_id = :banner_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':banner_id', $banner_id);
    $stmt->execute();
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tutor) {
        // Set tutor status as "logged in"
        $updateStatusQuery = "UPDATE tutors SET is_logged_in = 1 WHERE id = :tutor_id";
        $updateStmt = $conn->prepare($updateStatusQuery);
        $updateStmt->bindParam(':tutor_id', $tutor['id']);
        $updateStmt->execute();

        session_start();
        $_SESSION['tutor_id'] = $tutor['id'];
        $_SESSION['tutor_name'] = $tutor['tutor_name'];

        header('Location: tutor_dashboard.php');
    } else {
        echo "Invalid login credentials.";
    }
}
?>

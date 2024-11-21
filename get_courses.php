<?php
require 'db_connect.php';
//gets all the courses for a student or tutor given
$data = json_decode(file_get_contents('php://input'), true);
$userType = $data["userType"];
$id = $data["id"];

if($userType && $id){
    $table = $userType == "student" ? "student_courses" : "tutor_courses";
    $idType = $userType == "student" ? "student_id" : "tutor_id";

    $sql = "SELECT course_id FROM $table where $idType = '$id'";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die(json_encode(['status' => 'error', 'message' => 'Failed to prepare SQL statement.']));
    }

    $stmt->execute();
    if (!$stmt->execute()) {
        die(json_encode(['status' => 'error', 'message' => 'Failed to execute SQL query.']));
    }

    $courses = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode(['status' => 'success', 'courses' => $courses]);
}
else{
    echo json_encode(['status' => 'error', 'message' => 'usertype and id must be set']);
}

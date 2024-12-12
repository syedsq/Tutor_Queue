<?php
require 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$tutorID = $data['tutorID'];

if($tutorID){
    $query = "SELECT * FROM requests WHERE assigned_tutor = '$tutorID' ORDER BY submission_time";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $student_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($student_requests) > 0){
        echo json_encode(['status' => 'success', 'students' => $student_requests]);
    }
    else echo json_encode(['status' => 'error', 'message' => 'Could not retrieve student requests for id: ' . $tutorID]);
}
else{
    echo json_encode(['status' => 'error', 'message' => 'No tutor ID provided.']);
}


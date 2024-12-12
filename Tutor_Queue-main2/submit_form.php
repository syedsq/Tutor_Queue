<?php
// Include database connection
require 'db_connect.php';

// Fetch student data from the form submission
$fullName = $_POST['fullName'];
$studentID = $_POST['studentID'];
$email = $_POST['email'];
$class = $_POST['class'];
$sessionType = $_POST['sessionType'];
$subject = $_POST['subject'];

//find all tutors that are logged in
$tutorQuery = "SELECT tutor_id from tutor_courses where course_id='$class'";
$stmt = $conn->prepare($tutorQuery);
$stmt->execute();
$tutorIDs = $stmt->fetchAll();

$availableTutors = [];
for ($i = 0; $i < count($tutorIDs); $i++) {
    $tutorID = $tutorIDs[$i]['tutor_id'];
    $isAvailableQuery = "SELECT is_logged_in from tutors where utsa_id='$tutorID'";
    $stmt = $conn->prepare($isAvailableQuery);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (isset($result['is_logged_in'])) {
        $isAvailable = $result['is_logged_in'];
        if ($isAvailable) {
            array_push($availableTutors, $tutorID);
            echo "<script> console.log('Available: $tutorID');</script>";
        } else {
            echo "<script> console.log('Unavailable: $tutorID');</script>";
        }
    } else {
        echo "<script> console.log('Could not extract is_logged_in for tutor: $tutorID');</script>";
    }

}

//find most available tutor
$mostAvailableTutorID = $availableTutors[0];
$minQueueSize = PHP_INT_MAX;
for ($j = 0; $j < count($availableTutors); $j++) {
    //count how many requests have been assigned to each tutor
    $tutorID = $availableTutors[$j];
    $queueSizeQuery = "SELECT COUNT(*) as queue_size FROM requests where assigned_tutor='$tutorID' ";
    $stmt = $conn->prepare($queueSizeQuery);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $queueSize = $result['queue_size'];

    if ($queueSize < $minQueueSize) {
        $minQueueSize = $queueSize;
        $mostAvailableTutorID = $tutorID;
    }
}

//put into request
$requestQuery = "INSERT INTO requests (student_name, student_id, course_id, topic, session_type) VALUES (:fullName, :studentID, :courseID, :topic, :session_type)";
$stmt = $conn->prepare($requestQuery);
$stmt->bindParam(':fullName', $fullName);
$stmt->bindParam(':studentID', $studentID);
$stmt->bindParam(':courseID', $class);
$stmt->bindParam(':topic', $subject);
$stmt->bindParam(':session_type', $sessionType);
$stmt->execute();
$requestID = $conn->lastInsertId();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Confirmation</title>
    <link rel="stylesheet" href="./assets/main.css" type="text/css">

</head>

<body>

    <div class="container">
        <form method="post" action="submit_request.php">
            <h2>Select a Tutor</h2>
            <?php

            for ($i = 0; $i < count($availableTutors); $i++) {
                $tutorID = $availableTutors[$i];
                $tutorNameSQL = "SELECT tutor_name FROM tutors where utsa_id='$tutorID'";
                $stmt = $conn->prepare($tutorNameSQL);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $tutorName = $result['tutor_name'];

                if ($tutorID == $mostAvailableTutorID)
                    $tutorName .= " (Most Available)";

                echo "<label>";
                echo "<input type='radio' name='tutor_id' value='$availableTutors[$i]'> $tutorName</>";
                echo "</label><br>";
            }
            ?>
            <input type="hidden" name="request_id" value="<?php echo $requestID; ?>">

            <input type="submit" name="Submit Tutor Request">
        </form>

</body>

</html>
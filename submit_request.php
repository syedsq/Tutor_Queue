<?php
// Include database connection
require 'db_connect.php';

$assignedTutorID = $_POST['tutor_id'];
$requestID = $_POST['request_id'];

//update request with assigned tutor
$updateRequestQuery = "UPDATE requests SET assigned_tutor = '$assignedTutorID' WHERE id = '$requestID'";
$stmt = $conn->prepare($updateRequestQuery);
$stmt->execute();

$tutorInfo = "SELECT tutor_name FROM tutors WHERE utsa_id = '$assignedTutorID'";
$stmt = $conn->prepare($tutorInfo);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$tutorName = $result['tutor_name'];


// Get the student's position in the tutor's queue
$positionQuery = "SELECT COUNT(*) AS position FROM requests WHERE assigned_tutor = '$assignedTutorID' AND submission_time <= NOW()";
$positionStmt = $conn->prepare($positionQuery);
$positionStmt->execute();
$position = $positionStmt->fetch(PDO::FETCH_ASSOC)['position'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Confirmation</title>
    <link rel="stylesheet" href="./assets/main.css" type="text/css">
    <style>
        /* Page styling for consistency with UTSA theme */
        body {
            font-family: Arial, sans-serif;
            background-color: #00274d;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #ff8200;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .buttons a, .buttons button {
            background-color: #ff8200;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        .buttons a:hover, .buttons button:hover {
            background-color: #e67300;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Thank you for your submission!</h2>
    <p>You are number <?php echo $position; ?> in the queue for <?php echo htmlspecialchars($tutorName); ?>.</p>

    <div class="buttons">
        <a href="index.php">Home</a>
        <button onclick="window.open('feedback.php?student_id=<?php echo $requestID; ?>//', '_blank')">Give Feedback</button>
   </div>
</div>

</body>
</html>

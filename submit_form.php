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

$requestQuery = "INSERT INTO requests (student_name, student_id, course_id, topic) VALUES (:fullName, :studentID, :courseID, :topic)";
$stmt = $conn->prepare($requestQuery);
$stmt->bindParam(':fullName', $fullName);
$stmt->bindParam(':studentID', $studentID);
$stmt->bindParam(':courseID', $class);
$stmt->bindParam(':topic', $subject);
$stmt->execute();

// Get the student's position in the queue
$positionQuery = "SELECT COUNT(*) AS position FROM requests WHERE course_id = :course_id AND submission_time <= NOW()";
$positionStmt = $conn->prepare($positionQuery);
$positionStmt->bindParam(':course_id', $class);
$positionStmt->execute();
$position = $positionStmt->fetch(PDO::FETCH_ASSOC)['position'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Confirmation</title>
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
    <h2>Thank you for your submission, <?php echo htmlspecialchars($fullName); ?>!</h2>
    <p>You are number <?php echo $position; ?> in the queue for <?php echo htmlspecialchars($class); ?>.</p>
    
    <div class="buttons">
        <a href="index.php">Home</a>
        <button onclick="window.open('feedback.php?student_id=<?php echo $studentID; ?>', '_blank')">Give Feedback</button>
    </div>
</div>

</body>
</html>

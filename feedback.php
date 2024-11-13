<?php
// Include database connection
require 'db_connect.php';

// Fetch student ID from the query string
$studentID = isset($_GET['student_id']) ? $_GET['student_id'] : null;

// If the feedback is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = $_POST['feedback']; // "up" for thumbs-up, "down" for thumbs-down

    // Insert the feedback into the database
    $feedbackQuery = "INSERT INTO student_feedback (student_id, feedback) VALUES (:studentID, :feedback)";
    $stmt = $conn->prepare($feedbackQuery);
    $stmt->bindParam(':studentID', $studentID);
    $stmt->bindParam(':feedback', $feedback);
    $stmt->execute();

    // Redirect to home and close the window after submission
    echo "<script>
        alert('Feedback submitted! Thank you!');
        window.opener.location.href = 'index.php';
        window.close();
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #00274d;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .feedback-container {
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

        .feedback-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .feedback-buttons button {
            background-color: #ff8200;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            font-size: 18px;
        }

        .feedback-buttons button:hover {
            background-color: #e67300;
        }
    </style>
</head>
<body>

<div class="feedback-container">
    <h2>How was your experience?</h2>
    <form action="feedback.php?student_id=<?php echo htmlspecialchars($studentID); ?>" method="POST">
        <div class="feedback-buttons">
            <button type="submit" name="feedback" value="up">👍</button>
            <button type="submit" name="feedback" value="down">👎</button>
        </div>
    </form>
</div>

</body>
</html>

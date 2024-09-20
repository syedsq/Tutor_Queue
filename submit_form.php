<?php
// submit_form.php - Handles form submission, inserts data, and shows the queue position

// Include the database connection file
require 'db_connect.php';

// Initialize variables for displaying message
$message = "";
$queueNumber = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullName = $_POST['fullName'];
    $studentID = $_POST['studentID'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $sessionType = $_POST['sessionType'];
    $subject = $_POST['subject'];

    // Insert the student data into the database
    $sql = "INSERT INTO students (full_name, student_id, email, class, session_type, subject) 
            VALUES (:fullName, :studentID, :email, :class, :sessionType, :subject)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fullName', $fullName);
    $stmt->bindParam(':studentID', $studentID);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':class', $class);
    $stmt->bindParam(':sessionType', $sessionType);
    $stmt->bindParam(':subject', $subject);

    if ($stmt->execute()) {
        // Calculate the student's queue position based on the number of students in the same class
        $queueQuery = "SELECT COUNT(*) AS queue_count FROM students WHERE class = :class";
        $queueStmt = $conn->prepare($queueQuery);
        $queueStmt->bindParam(':class', $class);
        $queueStmt->execute();
        $queueResult = $queueStmt->fetch(PDO::FETCH_ASSOC);

        $queueNumber = $queueResult['queue_count'];
        $message = "Thank you for your submission, you're #$queueNumber in the queue for $class.";
    } else {
        $message = "Error: Could not submit your form. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Result</title>
    <style>
        /* Same theme as the index page with UTSA colors */
        body {
            font-family: Arial, sans-serif;
            background-color: #00274d; /* UTSA Navy Blue */
            color: white;
            margin: 0;
            padding: 0;
        }

        /* Center the result message */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: black;
            text-align: center;
        }

        h2 {
            color: #ff8200; /* UTSA Orange */
        }

        /* Back to home button */
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff8200; /* UTSA Orange */
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #e67300;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Submission Successful</h2>
        <p><?php echo $message; ?></p>

        <!-- Back to home button -->
        <a href="index.php" class="back-button">Back to Home</a>
    </div>

</body>
</html>

<?php
// Include database connection
require 'db_connect.php';

// Get student name and subject from the URL parameters
$studentName = isset($_GET['student_name']) ? htmlspecialchars($_GET['student_name']) : 'Unknown Student';
$subject = isset($_GET['subject']) ? htmlspecialchars($_GET['subject']) : 'Unknown Subject';

// If the form is submitted, save the survey data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentName = $_POST['student_name'];
    $subject = $_POST['subject'];
    $difficultyRating = $_POST['difficulty_rating'];
    $comments = $_POST['comments'];

    // Insert the survey result into the database
    $surveyQuery = "INSERT INTO student_feedback (student_name, subject, difficulty_rating, comments) VALUES (:student_name, :subject, :difficulty_rating, :comments)";
    $stmt = $conn->prepare($surveyQuery);
    $stmt->bindParam(':student_name', $studentName);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':difficulty_rating', $difficultyRating);
    $stmt->bindParam(':comments', $comments);
    $stmt->execute();

    echo "<script>alert('Survey submitted successfully!'); window.close();</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback Survey</title>
    <style>
        /* Styling for the survey form to match UTSA theme */
        body {
            font-family: Arial, sans-serif;
            background-color: #00274d; /* UTSA Navy Blue */
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .survey-container {
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #ff8200; /* UTSA Orange */
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select, textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .submit-button {
            background-color: #ff8200; /* UTSA Orange */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        .submit-button:hover {
            background-color: #e67300;
        }
    </style>
</head>
<body>

<div class="survey-container">
    <h2>Feedback Survey</h2>
    <p>How did <?php echo $studentName; ?> perform in <?php echo $subject; ?>?</p>

    <form action="survey.php" method="POST">
        <input type="hidden" name="student_name" value="<?php echo $studentName; ?>">
        <input type="hidden" name="subject" value="<?php echo $subject; ?>">

        <!-- Difficulty Rating -->
        <label for="difficulty_rating">Difficulty (1-10):</label>
        <select name="difficulty_rating" id="difficulty_rating" required>
            <option value="">-- Select Rating --</option>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>

        <!-- Comments -->
        <label for="comments">Comments:</label>
        <textarea name="comments" id="comments" rows="4" placeholder="Enter any comments about the student's performance"></textarea>

        <!-- Submit Button -->
        <button type="submit" class="submit-button">Submit Feedback</button>
    </form>
</div>

</body>
</html>

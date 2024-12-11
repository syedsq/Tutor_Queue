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
    <link rel="stylesheet" href="./assets/main5.css" type="text/css">

</head>

<body>

    <div class="hero-carousel">
        <div class="carousel-slide">
            <img src="assets/pics/cheers.jpg" alt="Slide 1">
            <img src="assets/pics/utsa main1.jpg" alt="Slide 2">
            <img src="assets/pics/Roadrunner-Statue2.jpg" alt="Slide 3">
            <img src="assets/pics/UTSA_Monument.jpg" alt="Slide 4">
            <img src="assets/pics/data.jpg" alt="Slide 5">
        </div>
    </div>

    <div class="header">
        <img src="assets/UTSA.png">
    </div>

    <div class="container-wrapper">
        <div class="top-line-image">
            <img src="assets/pics/san-antonio-skyline.png" alt="San Antonio Skyline">
        </div>
        <div class="container">
            <!-- <div class="survey-container"> -->
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
                <textarea name="comments" id="comments" rows="4"
                    placeholder="Enter any comments about the student's performance"></textarea>

                <!-- Submit Button -->
                <button type="submit" class="submit-button">Submit Feedback</button>
            </form>
        </div>
    </div>

    <?php
    include('templates/footer.php');
    ?>
</body>

</html>
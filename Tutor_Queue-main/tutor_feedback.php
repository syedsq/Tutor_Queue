<?php
// Get the pre-filled data from the query parameters
$tutorName = isset($_GET['tutor']) ? htmlspecialchars($_GET['tutor']) : '';
$courseName = isset($_GET['course']) ? htmlspecialchars($_GET['course']) : '';
$studentName = isset($_GET['student']) ? htmlspecialchars($_GET['student']) : '';
$sessionType = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Feedback</title>
    <link rel="stylesheet" href="./assets/main5.css" type="text/css">

</head>

<body>
    <div class="hero-carousel">
        <div class="carousel-slide">
            <img src="assets/pics/cheers.jpg" alt="Slide 1">
            <img src="assets/pics/cars.jpg" alt="Slide 2">
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
            <h2>Tutor Session Feedback</h2>

            <!-- Form to capture feedback -->
            <form action="submit_feedback.php" method="POST">
                <label for="tutorName">Select your (tutor) name</label>
                <input type="text" id="tutorName" name="tutorName" value="<?php echo $tutorName; ?>" readonly>

                <label for="courseName">Select the course for which you provided tutoring to the student</label>
                <input type="text" id="courseName" name="courseName" value="<?php echo $courseName; ?>" readonly>

                <label for="studentName">Enter the ID of the student who you helped</label>
                <input type="text" id="studentName" name="studentName" value="<?php echo $studentName; ?>" readonly>

                <label for="sessionType">Modality of tutoring</label>
                <input type="text" id="sessionType" name="sessionType" value="<?php echo $sessionType; ?>" readonly>

                <!-- Time slider -->
                <div class="slider-container">
                    <label for="timeSlider" class="slider-label">Enter the time (in minutes) for which you helped this
                        student</label>
                    <input type="range" id="timeSlider" name="sessionTime" min="0" max="100" step="10" value="0"
                        oninput="updateSliderValue(this.value)">
                    <div class="slider-value" id="sliderValue">Time (minutes): 0</div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">Submit Feedback</button>
            </form>
        </div>

        <script>
            // Update the displayed slider value
            function updateSliderValue(value) {
                document.getElementById('sliderValue').textContent = `Time (minutes): ${value}`;
            }
        </script>
    </div>

    <?php include('templates/footer.php'); ?>
</body>

</html>
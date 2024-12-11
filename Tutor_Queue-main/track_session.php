<?php
// track_session.php - Tracks session time for a student

$student_id = $_GET['student_id'];
$full_name = $_GET['full_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Session for <?php echo htmlspecialchars($full_name); ?></title>
    <link rel="stylesheet" href="./assets/main5.css" type="text/css">

    <script>
        // Timer functionality
        let startTime = Date.now();

        function updateTimer() {
            let now = Date.now();
            let elapsed = Math.floor((now - startTime) / 1000);
            let minutes = Math.floor(elapsed / 60);
            let seconds = elapsed % 60;
            document.getElementById('timer').textContent = `${minutes}m ${seconds}s`;
        }

        setInterval(updateTimer, 1000);
    </script>
</head>

<body>
    <div class="hero-carousel">
        <div class="carousel-slide">
            <img src="assets/pics/cars.png" alt="Slide 1">
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
            <h2>Session with <?php echo htmlspecialchars($full_name); ?></h2>
            <div class="timer" id="timer">0m 0s</div>
            <form action="end_session.php" method="POST">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <button type="submit" class="end-button">End Session</button>
            </form>
        </div>
    </div>

    <?php
    include('templates/footer.php');
    ?>
</body>

</html>
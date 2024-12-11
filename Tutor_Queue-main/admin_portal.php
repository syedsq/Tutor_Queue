<?php
// Include the database connection
require 'db_connect.php';
?>

<?php
// include('templates/header.php'); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <link rel="stylesheet" href="./assets/main5.css" type="text/css">
</head>

<body>
    <div class="hero-carousel">
        <div class="carousel-slide">
            <img src="assets/pics/cheers.jpg" alt="Slide 1">
            <img src="assets/pics/utsa main1.jpg" alt="Slide 2">
            <img src="assets/pics/cars.jpg" alt="Slide 3">
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
            <h2>Admin Portal</h2>

            <div class="button-container">
                <!-- Add Tutor Button -->
                <a href="manage_tutors.php" class="button">Add/Remove Tutor</a>

                <!-- View Results Button -->
                <a href="results.php" class="button">View Results</a>

                <!-- Upload CSV Button -->
                <a href="upload_csv.php" class="button">Upload CSV File</a>
            </div>
        </div>
    </div>

    <?php include('templates/footer.php'); ?>
</body>

</html>
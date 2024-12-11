<?php
// Include database connection
require 'db_connect.php';

$currentDay = date('l'); // e.g., "Monday"
$currentTime = date('H:i:s'); // e.g., "14:30:00"

// Fetch available tutors and their classes based on current time and day
try {
    $availabilityQuery = "
        SELECT t.subject, t.tutor_name, 
        (SELECT COUNT(*) FROM students WHERE assigned_tutor = t.id) AS queue_count
        FROM tutor_availability a
        JOIN tutors t ON a.tutor_id = t.id
        WHERE a.day_of_week = :currentDay
        AND :currentTime BETWEEN a.start_time AND a.end_time
    ";
    $availabilityStmt = $conn->prepare($availabilityQuery);
    $availabilityStmt->bindParam(':currentDay', $currentDay);
    $availabilityStmt->bindParam(':currentTime', $currentTime);
    $availabilityStmt->execute();
    $availableClasses = $availabilityStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching tutor availability: " . $e->getMessage());
}

// Session types
$sessionTypes = ['online' => 'Online', 'inperson' => 'In-Person'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Success Tutoring Services</title>
    <link rel="stylesheet" href="./assets/main5.css" type="text/css">
    <script src="auth.js"></script>
    <script async crossorigin="anonymous"
        data-clerk-publishable-key="pk_test_d2lsbGluZy1kaW5vc2F1ci05MS5jbGVyay5hY2NvdW50cy5kZXYk"
        src="https://willing-dinosaur-91.clerk.accounts.dev/npm/@clerk/clerk-js@latest/dist/clerk.browser.js"
        type="text/javascript"></script>
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
            <h2 id="title">CS LAB Tutoring</h2>

            <!-- Form to capture student info -->
            <form action="submit_form.php" method="POST">

                <!-- Full name -->
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" readonly required>

                <!-- Student ID -->
                <label for="studentID">Student ID</label>
                <input type="text" id="studentID" name="studentID" placeholder="e.g., abc123" readonly required>

                <!-- Email -->
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="firstname.lastname@my.utsa.edu" readonly
                    required>

                <!-- Dynamic Class dropdown -->
                <label for="class">Select Class</label>
                <select id="class" name="class" required>
                    <option value="">-- Select a Class --</option>
                    <?php foreach ($availableClasses as $class): ?>
                        <option value="<?php echo htmlspecialchars($class['subject']); ?>">
                            <?php echo htmlspecialchars($class['subject']) . " (Tutor: " . htmlspecialchars($class['tutor_name']) . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Session Type -->
                <label for="sessionType">Session Type</label>
                <select id="sessionType" name="sessionType">
                    <option value="">-- Select Session Type --</option>
                    <?php foreach ($sessionTypes as $value => $label): ?>
                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Subject -->
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Enter subject" required>

                <!-- Submit -->
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <?php
    include('templates/footer.php');
    ?>
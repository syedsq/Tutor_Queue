<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Check if the tutor is logged in
if (!isset($_SESSION['tutor_id'])) {
    header('Location: tutor_login.php');
    exit();
}

// Include the database connection
require 'db_connect.php';

// Fetch tutor's current availability
$tutorId = $_SESSION['tutor_id'];

$availabilityQuery = "SELECT * FROM tutor_availability WHERE tutor_id = :tutor_id";
$availabilityStmt = $conn->prepare($availabilityQuery);
$availabilityStmt->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
$availabilityStmt->execute();
$availabilityData = $availabilityStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for updating availability
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Start transaction for atomicity
        $conn->beginTransaction();

        // Delete existing availability for this tutor
        $deleteQuery = "DELETE FROM tutor_availability WHERE tutor_id = :tutor_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
        if (!$deleteStmt->execute()) {
            throw new Exception("Failed to delete existing availability.");
        }

        $updatedSchedule = [];  // Store schedule summary for tutors table

        // Insert new availability
        foreach ($_POST['day_of_week'] as $index => $day) {
            $start_time = $_POST['start_time'][$index];
            $end_time = $_POST['end_time'][$index];

            // Only insert if times are provided
            if (!empty($start_time) && !empty($end_time)) {
                $insertQuery = "INSERT INTO tutor_availability (tutor_id, day_of_week, start_time, end_time) 
                                VALUES (:tutor_id, :day_of_week, :start_time, :end_time)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bindParam(':tutor_id', $tutorId);
                $insertStmt->bindParam(':day_of_week', $day);
                $insertStmt->bindParam(':start_time', $start_time);
                $insertStmt->bindParam(':end_time', $end_time);
                if (!$insertStmt->execute()) {
                    throw new Exception("Failed to insert availability for $day.");
                }

                // Format schedule summary for tutors table
                $formattedTime = date("g:i A", strtotime($start_time)) . ' - ' . date("g:i A", strtotime($end_time));
                $updatedSchedule[] = $day . ' ' . $formattedTime;
            }
        }

        // Update the tutor's schedule in the tutors table with a summary of availability
        if (!empty($updatedSchedule)) {
            $scheduleSummary = implode(', ', $updatedSchedule);
            $updateTutorQuery = "UPDATE tutors SET schedule = :schedule WHERE id = :tutor_id";
            $updateTutorStmt = $conn->prepare($updateTutorQuery);
            $updateTutorStmt->bindParam(':schedule', $scheduleSummary);
            $updateTutorStmt->bindParam(':tutor_id', $tutorId);
            if (!$updateTutorStmt->execute()) {
                throw new Exception("Failed to update tutor's schedule.");
            }
        }

        // Commit the transaction
        $conn->commit();
        echo "<script>alert('Availability updated!'); window.location.href='tutor_dashboard.php';</script>";

    } catch (Exception $e) {
        // Rollback on failure
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tutor Availability</title>
    <link rel="stylesheet" href="./assets/main.css" type="text/css">
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #ff8200;
            
        } */

        .schedule-form {
            margin-top: 20px;
        }

        .day-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="time"] {
            width: 120px;
            padding: 5px;
        }

        input[type="time"]:disabled {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #ff8200;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-btn:hover {
            background-color: #e67300;
        }
    </style>
    <script>
        function toggleTimeFields(checkbox, index) {
            const startTimeField = document.getElementsByName('start_time[]')[index];
            const endTimeField = document.getElementsByName('end_time[]')[index];

            if (checkbox.checked) {
                startTimeField.disabled = false;
                endTimeField.disabled = false;
            } else {
                startTimeField.disabled = true;
                endTimeField.disabled = true;
                startTimeField.value = '';
                endTimeField.value = '';
            }
        }

        window.onload = function () {
            const checkboxes = document.getElementsByName('day_of_week[]');
            checkboxes.forEach((checkbox, index) => {
                checkbox.addEventListener('change', function () {
                    toggleTimeFields(this, index);
                });

                // Trigger the toggle on page load based on initial state
                toggleTimeFields(checkbox, index);
            });
        }
    </script>
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

    <div class="container">
        <h2>Manage Availability</h2>

        <form method="POST" class="schedule-form">
            <?php
            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach ($daysOfWeek as $index => $day):
                // Check if there's existing availability for this day
                $startTime = '';
                $endTime = '';
                foreach ($availabilityData as $availability) {
                    if ($availability['day_of_week'] == $day) {
                        $startTime = $availability['start_time'];
                        $endTime = $availability['end_time'];
                    }
                }
                ?>
                <div class="day-row">
                    <label>
                        <input type="checkbox" name="day_of_week[]" value="<?php echo $day; ?>" <?php if ($startTime)
                               echo 'checked'; ?>>
                        <?php echo $day; ?>
                    </label>
                    <input type="time" name="start_time[]" value="<?php echo $startTime; ?>" <?php if (!$startTime)
                           echo 'disabled'; ?> placeholder="Start Time">
                    <input type="time" name="end_time[]" value="<?php echo $endTime; ?>" <?php if (!$endTime)
                           echo 'disabled'; ?> placeholder="End Time">
                </div>
            <?php endforeach; ?>

            <button type="submit" class="submit-btn">Save Availability</button>
        </form>
    </div>
    <?php include('templates/footer.php'); ?>
</body>

</html>
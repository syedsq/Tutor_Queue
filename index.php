<?php
// Include database connection
require 'db_connect.php';

// Get current day and time
$currentDay = date('l'); // e.g., "Monday"
$currentTime = date('H:i:s'); // e.g., "14:30:00"

// Fetch available tutors and their classes based on current time and day
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

// Fetch session types
$sessionTypes = ['online' => 'Online', 'inperson' => 'In-Person'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Success Tutoring Services</title>
    <style>
        /* Basic page setup with UTSA colors */
        body {
            font-family: Arial, sans-serif;
            background-color: #00274d; /* UTSA Navy Blue */
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: black;
        }

        h2 {
            text-align: center;
            color: #ff8200; /* UTSA Orange */
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #ff8200; /* UTSA Orange */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            padding: 10px;
        }

        input[type="submit"]:hover {
            background-color: #e67300;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>CS LAB Tutoring</h2>
        
        <!-- Form to capture student info -->
        <form action="submit_form.php" method="POST">

            <!-- Full name -->
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" required>

            <!-- Student ID -->
            <label for="studentID">Student ID</label>
            <input type="text" id="studentID" name="studentID" required>

            <!-- Email -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <!-- Dynamic Class dropdown with tutor details based on availability -->
            <label for="class">Select Class</label>
            <select id="class" name="class" required>
                <option value="">-- Select a Class --</option>
                <?php foreach ($availableClasses as $class): ?>
                    <option value="<?php echo htmlspecialchars($class['subject']); ?>">
                        <?php echo htmlspecialchars($class['subject']) . " (Tutor: " . htmlspecialchars($class['tutor_name']) . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Session Type dropdown -->
            <label for="sessionType">Session Type</label>
            <select id="sessionType" name="sessionType">
                <option value="">-- Select Session Type --</option>
                <?php foreach ($sessionTypes as $value => $label): ?>
                    <option value="<?php echo $value; ?>">
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Subject -->
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required>

            <!-- Submit button -->
            <input type="submit" value="Submit">
        </form>
    </div>

</body>
</html>

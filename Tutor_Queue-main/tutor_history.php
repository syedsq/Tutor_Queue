<?php
// Include the database connection file
require 'db_connect.php';

// Query to get tutor history (adjust this if you track specific tutor ID)
$query = "SELECT * FROM tutor_sessions ORDER BY session_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$tutorHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor History</title>
    <link rel="stylesheet" href="./assets/main5.css" type="text/css">

</head>

<body>
    <div class="header">
        <img src="assets/UTSA.png">
    </div>

    <div class="container">
        <h2>Tutor History</h2>

        <!-- Tutor History Table -->
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Duration</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tutorHistory as $history): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($history['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($history['subject']); ?></td>
                        <td><?php echo htmlspecialchars($history['session_duration']); ?></td>
                        <td><?php echo htmlspecialchars($history['session_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
    include('templates/footer.php');
    ?>

</body>

</html>
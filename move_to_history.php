<?php
// Include the database connection file
require 'db_connect.php';

// Query to get tutor history
$query = "SELECT student_name, subject, session_duration, session_date FROM tutor_sessions ORDER BY session_date DESC";
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #ff8200; /* UTSA Orange */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Tutor History</h2>

        <!-- Tutor History Table -->
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Duration (minutes)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tutorHistory as $history): ?>
                <tr>
                    <td><?php echo htmlspecialchars($history['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($history['subject']); ?></td>
                    <td><?php echo htmlspecialchars($history['session_duration']); ?> minutes</td>
                    <td><?php echo htmlspecialchars($history['session_date']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

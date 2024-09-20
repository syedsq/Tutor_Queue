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
    <style>
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

        .container {
            width: 80%;
            max-width: 1200px;
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #ff8200; /* UTSA Orange */
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
            background-color: #ff8200;
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

</body>
</html>

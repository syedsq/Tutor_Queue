<?php
// Include the database connection
require 'db_connect.php';

// Fetch data from each table
$studentsQuery = "SELECT * FROM students";
$studentsStmt = $conn->prepare($studentsQuery);
$studentsStmt->execute();
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);

$tutorsQuery = "SELECT * FROM tutors";
$tutorsStmt = $conn->prepare($tutorsQuery);
$tutorsStmt->execute();
$tutors = $tutorsStmt->fetchAll(PDO::FETCH_ASSOC);

$sessionsQuery = "SELECT * FROM tutor_sessions";
$sessionsStmt = $conn->prepare($sessionsQuery);
$sessionsStmt->execute();
$sessions = $sessionsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #ff8200;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
    <h2>Admin Results</h2>

    <h3>Student Data</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Student ID</th>
            <th>Email</th>
            <th>Class</th>
            <th>Session Type</th>
            <th>Subject</th>
            <th>Submission Time</th>
        </tr>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?php echo $student['id']; ?></td>
            <td><?php echo htmlspecialchars($student['full_name']); ?></td>
            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
            <td><?php echo htmlspecialchars($student['email']); ?></td>
            <td><?php echo htmlspecialchars($student['class']); ?></td>
            <td><?php echo htmlspecialchars($student['session_type']); ?></td>
            <td><?php echo htmlspecialchars($student['subject']); ?></td>
            <td><?php echo $student['submission_time']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Tutor Data</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Subject</th>
            <th>Email</th>
            <th>Banner ID</th>
        </tr>
        <?php foreach ($tutors as $tutor): ?>
        <tr>
            <td><?php echo $tutor['id']; ?></td>
            <td><?php echo htmlspecialchars($tutor['tutor_name']); ?></td>
            <td><?php echo htmlspecialchars($tutor['subject']); ?></td>
            <td><?php echo htmlspecialchars($tutor['email']); ?></td>
            <td><?php echo htmlspecialchars($tutor['banner_id']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Session Data</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Student ID</th>
            <th>Subject</th>
            <th>Session Type</th>
            <th>Session Duration (min)</th>
            <th>Session Date</th>
        </tr>
        <?php foreach ($sessions as $session): ?>
        <tr>
            <td><?php echo $session['id']; ?></td>
            <td><?php echo htmlspecialchars($session['student_name']); ?></td>
            <td><?php echo htmlspecialchars($session['student_id']); ?></td>
            <td><?php echo htmlspecialchars($session['subject']); ?></td>
            <td><?php echo htmlspecialchars($session['session_type']); ?></td>
            <td><?php echo htmlspecialchars($session['session_duration']); ?></td>
            <td><?php echo $session['session_date']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>

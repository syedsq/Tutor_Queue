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
    <style>
        /* Basic styles for the session tracker */
        body {
            font-family: Arial, sans-serif;
            background-color: #00274d;
            color: white;
            text-align: center;
            padding: 50px;
        }

        .container {
            background-color: #ffffff;
            color: black;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }

        .timer {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .end-button {
            background-color: #ff8200;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        .end-button:hover {
            background-color: #e67300;
        }
    </style>

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

    <div class="container">
        <h2>Session with <?php echo htmlspecialchars($full_name); ?></h2>

        <!-- Timer display -->
        <div class="timer" id="timer">0m 0s</div>

        <!-- End session button -->
        <form action="end_session.php" method="POST">
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
            <button type="submit" class="end-button">End Session</button>
        </form>
    </div>

</body>
</html>

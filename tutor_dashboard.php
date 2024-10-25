<?php
// Start the session
session_start();

// Check if the tutor is logged in
if (!isset($_SESSION['tutor_id'])) {
    header('Location: tutor_login.php');
    exit();
}

// Include database connection
require 'db_connect.php';

// Define how many results per page
$resultsPerPage = 10;

// Get the current page or set a default
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = (int)$_GET['page'];
} else {
    $currentPage = 1; // Default page is 1
}

// Determine the SQL LIMIT starting number
$startingLimit = ($currentPage - 1) * $resultsPerPage;

// Query to get total number of students for pagination
$totalQuery = "SELECT COUNT(*) FROM students";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->execute();
$totalStudents = $totalStmt->fetchColumn();

// Calculate total pages
$totalPages = ceil($totalStudents / $resultsPerPage);

// Fetch students for the current page
$query = "SELECT * FROM students ORDER BY submission_time ASC LIMIT :startingLimit, :resultsPerPage";
$stmt = $conn->prepare($query);
$stmt->bindParam(':startingLimit', $startingLimit, PDO::PARAM_INT);
$stmt->bindParam(':resultsPerPage', $resultsPerPage, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch tutor's name and subjects from the database
$tutorQuery = "SELECT subject FROM tutors WHERE id = :tutor_id";
$tutorStmt = $conn->prepare($tutorQuery);
$tutorStmt->bindParam(':tutor_id', $_SESSION['tutor_id'], PDO::PARAM_INT);
$tutorStmt->execute();
$tutorDetails = $tutorStmt->fetch(PDO::FETCH_ASSOC);

// Set the tutor's subjects
$tutor_subjects = $tutorDetails['subject'];
$tutor_name = $_SESSION['tutor_name']; // Assuming tutor's name is stored in session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
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

        .dashboard {
            display: flex;
            width: 80%;
            max-width: 1200px;
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .student-list {
            flex: 1;
            margin-right: 20px;
        }

        .student-list h3 {
            color: #ff8200; /* UTSA Orange */
            text-align: center;
            margin-bottom: 10px;
        }

        .student-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
            border: 1px solid #ccc;
        }

        .student-list li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }

        .student-list li:hover {
            background-color: #f0f0f0;
        }

        .session-details {
            flex: 2;
            text-align: center;
            padding: 20px;
            border-left: 2px solid #ccc;
        }

        .session-details h2 {
            color: #ff8200; /* UTSA Orange */
            margin-bottom: 20px;
        }

        .session-controls {
            margin-bottom: 20px;
        }

        .session-controls button {
            padding: 10px 20px;
            margin: 0 5px;
            background-color: #ff8200; /* UTSA Orange */
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        .session-controls button:hover {
            background-color: #e67300;
        }

        .timer-container {
            margin-top: 20px;
        }

        .timer {
            font-size: 20px;
            margin-bottom: 10px;
            background-color: #ff8200;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            width: 100px;
        }

        .end-session-btn {
            padding: 5px 10px;
            background-color: #e67300;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #ff8200; /* UTSA Orange */
            color: white;
            border: 1px solid #ff8200;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        /* Tutor History and Manage Availability Buttons */
        .tutor-buttons {
            margin-top: 20px;
            text-align: center;
        }

        .tutor-history-btn, .manage-availability-btn {
            background-color: #ff8200;
            color: white;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
            display: inline-block;
            cursor: pointer;
        }

        .tutor-history-btn:hover, .manage-availability-btn:hover {
            background-color: #e67300;
        }

        .greeting {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="dashboard">
        <!-- List of Students -->
        <div class="student-list">
            <h3>List of Students</h3>
            <ul id="studentList">
                <!-- Dynamic Student List -->
                <?php foreach ($students as $student): ?>
                <li data-student-name="<?php echo htmlspecialchars($student['full_name']); ?>" data-student-id="<?php echo $student['id']; ?>">
                    <?php echo htmlspecialchars($student['full_name']); ?> - <?php echo htmlspecialchars($student['subject']); ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Session Details -->
        <div class="session-details">
            <!-- Greeting -->
            <div class="greeting">
                <h2>Welcome, <?php echo htmlspecialchars($tutor_name); ?>!</h2>
                <p>You're tutoring: <?php echo htmlspecialchars($tutor_subjects); ?></p>
            </div>

            <h2 id="sessionPrompt">Click a student to start a session</h2>
            <div class="session-controls" id="sessionControls" style="display: none;">
                <button id="startSession">Yes</button>
                <button id="cancelSession">No</button>
            </div>

            <!-- Timer Section -->
            <div class="timer-container" id="timerContainer"></div>

            <!-- Tutor History and Manage Availability Buttons -->
            <div class="tutor-buttons">
                <a href="tutor_history.php" target="_blank" class="tutor-history-btn">Tutor History</a>
                <a href="tutor_schedule.php" class="manage-availability-btn">Manage Availability</a>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                <a href="tutor_dashboard.php?page=<?php echo $page; ?>" class="<?php if ($page == $currentPage) echo 'active'; ?>">
                    <?php echo $page; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>

    <script>
        let timers = {}; // Object to store timers for each student
        let startTimes = {}; // Object to store start times for each student
        let selectedStudentId;
        let selectedStudentName;

        const studentListItems = document.querySelectorAll("#studentList li");
        const sessionPrompt = document.getElementById("sessionPrompt");
        const sessionControls = document.getElementById("sessionControls");
        const timerContainer = document.getElementById("timerContainer");

        // Click event for each student list item
        studentListItems.forEach(item => {
            item.addEventListener('click', function () {
                selectedStudentId = this.dataset.studentId;
                selectedStudentName = this.dataset.studentName;

                sessionPrompt.textContent = `Start session with ${selectedStudentName}?`;
                sessionControls.style.display = "block";
            });
        });

        // Start session (Yes button)
        document.getElementById('startSession').addEventListener('click', function () {
            sessionPrompt.textContent = `Session started with ${selectedStudentName}`;
            sessionControls.style.display = "none";

            // Remove the student from the list
            const selectedItem = document.querySelector(`li[data-student-id='${selectedStudentId}']`);
            selectedItem.remove();

            // Create a new timer for this student
            startTimes[selectedStudentId] = Date.now();
            timers[selectedStudentId] = setInterval(() => updateTimer(selectedStudentId), 1000);

            // Add the timer to the page
            const newTimer = document.createElement('div');
            newTimer.setAttribute('id', `timer-${selectedStudentId}`);
            newTimer.innerHTML = `
                <div>
                    <span>${selectedStudentName} - <span class="timer" id="timer-display-${selectedStudentId}">00:00</span></span>
                    <button class="end-session-btn" onclick="endSession('${selectedStudentId}', '${selectedStudentName}')">End</button>
                </div>
            `;
            timerContainer.appendChild(newTimer);
        });

        // Cancel session (No button)
        document.getElementById('cancelSession').addEventListener('click', function () {
            sessionPrompt.textContent = "Click a student to start a session";
            sessionControls.style.display = "none";
        });

        // Function to update the timer display
        function updateTimer(studentId) {
            const currentTime = Date.now();
            const elapsedTime = Math.floor((currentTime - startTimes[studentId]) / 1000);
            const minutes = Math.floor(elapsedTime / 60);
            const seconds = elapsedTime % 60;
            document.getElementById(`timer-display-${studentId}`).textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        // End session for a specific student
        function endSession(studentId, studentName) {
            clearInterval(timers[studentId]); // Stop the timer
            alert(`Session with ${studentName} has ended!`);

            const timerElement = document.getElementById(`timer-${studentId}`);
            timerElement.remove(); // Remove the timer from the page

            // Move the student to history and delete from the current students table
            fetch('move_to_history.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `studentId=${studentId}&studentName=${encodeURIComponent(studentName)}`
            }).then(response => {
                if (!response.ok) {
                    alert('Error moving student to history');
                }
            });
        }
    </script>

</body>
</html>

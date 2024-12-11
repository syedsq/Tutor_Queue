<?php
// Include the database connection file
require 'db_connect.php';

// Define how many results per page
$resultsPerPage = 10;

// Get the current page or set a default
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = (int) $_GET['page'];
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
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

    <div class="dashboard">
        <!-- List of Students -->
        <div class="student-list">
            <h3>List of Students</h3>
            <ul id="studentList">
                <?php foreach ($students as $student): ?>
                    <li data-student-name="<?php echo htmlspecialchars($student['full_name']); ?>"
                        data-student-id="<?php echo $student['id']; ?>"
                        data-subject="<?php echo htmlspecialchars($student['subject']); ?>">
                        <?php echo htmlspecialchars($student['full_name']) . ' - ' . htmlspecialchars($student['subject']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="tutor_history.php" target="_blank" class="tutor-history-btn">Tutor History</a>
        </div>

        <!-- Session Details -->
        <div class="session-details">
            <h2 id="sessionPrompt">Click a student to start a session</h2>
            <div class="session-controls" id="sessionControls" style="display: none;">
                <button id="startSession">Yes</button>
                <button id="cancelSession">No</button>
            </div>

            <!-- Timer Section -->
            <div class="timer-container" id="timerContainer"></div>
        </div>
    </div>

    <script>
        let timers = {}; // Object to store timers for each student
        let startTimes = {}; // Object to store start times for each student
        let selectedStudentId;
        let selectedStudentName;
        let selectedStudentSubject;

        const studentListItems = document.querySelectorAll("#studentList li");
        const sessionPrompt = document.getElementById("sessionPrompt");
        const sessionControls = document.getElementById("sessionControls");
        const timerContainer = document.getElementById("timerContainer");

        studentListItems.forEach(item => {
            item.addEventListener('click', function () {
                selectedStudentId = this.dataset.studentId;
                selectedStudentName = this.dataset.studentName;
                selectedStudentSubject = this.dataset.subject;

                sessionPrompt.textContent = `Start session with ${selectedStudentName}?`;
                sessionControls.style.display = "block";
            });
        });

        document.getElementById('startSession').addEventListener('click', function () {
            sessionPrompt.textContent = `Session started with ${selectedStudentName}`;
            sessionControls.style.display = "none";

            const selectedItem = document.querySelector(`li[data-student-id='${selectedStudentId}']`);
            selectedItem.remove();

            startTimes[selectedStudentId] = Date.now();
            timers[selectedStudentId] = setInterval(() => updateTimer(selectedStudentId), 1000);

            const newTimer = document.createElement('div');
            newTimer.setAttribute('id', `timer-${selectedStudentId}`);
            newTimer.classList.add('timer-wrapper');
            newTimer.innerHTML = `
                <div class="timer-display" id="timer-display-${selectedStudentId}">00:00</div>
                <button class="end-session-btn" onclick="endSession('${selectedStudentId}', '${selectedStudentName}', '${selectedStudentSubject}')">End</button>
            `;
            timerContainer.appendChild(newTimer);
        });

        document.getElementById('cancelSession').addEventListener('click', function () {
            sessionPrompt.textContent = "Click a student to start a session";
            sessionControls.style.display = "none";
        });

        function updateTimer(studentId) {
            const currentTime = Date.now();
            const elapsedTime = Math.floor((currentTime - startTimes[studentId]) / 1000);
            const minutes = Math.floor(elapsedTime / 60);
            const seconds = elapsedTime % 60;
            document.getElementById(`timer-display-${studentId}`).textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function endSession(studentId, studentName, subject) {
            clearInterval(timers[studentId]);
            alert(`Session with ${studentName} has ended!`);

            const timerElement = document.getElementById(`timer-${studentId}`);
            timerElement.remove();

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

            window.open(`survey.php?student_name=${encodeURIComponent(studentName)}&subject=${encodeURIComponent(subject)}`, '_blank');
        }
    </script>

    <?php include('templates/footer.php'); ?>
</body>

</html>
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

// Fetch students for the current page
$query = "SELECT * FROM requests ORDER BY submission_time ASC LIMIT :startingLimit, :resultsPerPage";
$stmt = $conn->prepare($query);
$stmt->bindParam(':startingLimit', $startingLimit, PDO::PARAM_INT);
$stmt->bindParam(':resultsPerPage', $resultsPerPage, PDO::PARAM_INT);
$stmt->execute();
$student_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
    <link href="./assets/style.css" rel="stylesheet">
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

        /* Tutor History Button */
        .tutor-history-btn {
            background-color: #ff8200;
            color: white;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
            cursor: pointer;
        }

        .tutor-history-btn:hover {
            background-color: #e67300;
        }
    </style>
    <script
            async
            crossorigin="anonymous"
            data-clerk-publishable-key="pk_test_d2lsbGluZy1kaW5vc2F1ci05MS5jbGVyay5hY2NvdW50cy5kZXYk"
            src="https://willing-dinosaur-91.clerk.accounts.dev/npm/@clerk/clerk-js@latest/dist/clerk.browser.js"
            type="text/javascript"
    ></script>

    <script>
        window.addEventListener('load', async () =>{
            await Clerk.load();
            if(!Clerk.user){
                window.location.href = 'user-auth/signin.php';
            }
            else{
                const userButtonDiv = document.getElementById('user-button');
                const clerkUserId = Clerk.user.id;
                Clerk.mountUserButton(userButtonDiv);
                fetch("./user-auth/get_user_by_id.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ clerk_user_id: clerkUserId })
                })
                    .then(response => response.json())
                    .then(data =>{
                        if(data.status === "success"){
                            const user = data.user;
                            //check if user is a tutor
                            fetch('./user-auth/is_tutor_user.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({utsa_id: user.utsa_id})
                            })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                    if(data.status === 'error'){
                                        window.location.href = 'index.php';
                                    }
                                    else{
                                        const user = data.user;
                                        fetch('get_tutor_requests.php', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({tutorID: user.utsa_id})
                                        })
                                            .then(response => response.json())
                                            .then(data => {
                                                if(data.status === "success"){
                                                    console.log(data);

                                                    const sessionPrompt = document.getElementById("sessionPrompt");
                                                    const sessionControls = document.getElementById("sessionControls");
                                                    const timerContainer = document.getElementById("timerContainer");

                                                    const listOfStudentsElement = document.getElementById('studentList');
                                                    const students = data.students;

                                                    for(const student of students){
                                                        var li = document.createElement('li');
                                                        li.setAttribute("data-student-name", student.student_name);
                                                        li.setAttribute("data-student-id", student.student_id);
                                                        li.setAttribute("data-subject", student.topic);
                                                        li.textContent = `${student.student_name} - ${student.topic}`;

                                                        li.addEventListener('click', function () {
                                                            selectedStudentId = this.dataset.studentId;
                                                            selectedStudentName = this.dataset.studentName;
                                                            selectedStudentSubject = this.dataset.subject;

                                                            sessionPrompt.textContent = `Start session with ${selectedStudentName}?`;
                                                            sessionControls.style.display = "block";
                                                        });

                                                        listOfStudentsElement.appendChild(li);
                                                    }
                                                }
                                                else {
                                                    console.log(data);
                                                }
                                            })
                                    }
                                })

                        }
                        else{
                            window.location.href = 'user-auth/signin.php';
                        }
                    })
            }

        })
    </script>
</head>
<body>
    <div class="user-button">
        <div id="user-button"></div>
    </div>
    <div class="dashboard">
        <!-- List of Students -->
        <div class="student-list">
            <h3>Tutoring Requests</h3>
            <ul id="studentList">
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

<!--        <div class="pagination">-->
<!--            --><?php //for ($page = 1; $page <= $totalPages; $page++): ?>
<!--                <a href="tutor_dashboard.php?page=--><?php //echo $page; ?><!--" class="--><?php //if ($page == $currentPage) echo 'active'; ?><!--">-->
<!--                    --><?php //echo $page; ?>
<!--                </a>-->
<!--            --><?php //endfor; ?>
<!--        </div>-->
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

            //update requests

            const selectedItem = document.querySelector(`li[data-student-id='${selectedStudentId}']`);
            selectedItem.remove();

            startTimes[selectedStudentId] = Date.now();
            timers[selectedStudentId] = setInterval(() => updateTimer(selectedStudentId), 1000);

            const newTimer = document.createElement('div');
            newTimer.setAttribute('id', `timer-${selectedStudentId}`);
            newTimer.innerHTML = `
                <div>
                    <span>${selectedStudentName} - <span class="timer" id="timer-display-${selectedStudentId}">00:00</span></span>
                    <button class="end-session-btn" onclick="endSession('${selectedStudentId}', '${selectedStudentName}', '${selectedStudentSubject}')">End</button>
                </div>
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
</body>
</html>

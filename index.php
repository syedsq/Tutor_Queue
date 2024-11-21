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
    <script
            async
            crossorigin="anonymous"
            data-clerk-publishable-key="pk_test_d2lsbGluZy1kaW5vc2F1ci05MS5jbGVyay5hY2NvdW50cy5kZXYk"
            src="https://willing-dinosaur-91.clerk.accounts.dev/npm/@clerk/clerk-js@latest/dist/clerk.browser.js"
            type="text/javascript"
    ></script>
    <script src="auth.js"></script>
    <link href="./assets/style.css" rel="stylesheet">
    <script>
        // Load Clerk and redirect if user is not signed in
        window.addEventListener('load', async () => {
            await Clerk.load();

            // Redirect if no user is signed in
            if (!Clerk.user) {
                window.location.href = 'user-auth/signin.php';
            }
            //clerk user found
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
                    .then(data => {
                        //if response is successful we got the user data
                        if (data.status === "success") {
                            const user = data.user;
                            //checking if user is an admin
                            fetch("./user-auth/is_admin_user.php", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({utsa_id: user.utsa_id})
                            })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                    //success means that its an admin and redirect to admin portal
                                    if (data.status === "success") {
                                        document.location.href = "admin_portal.php";
                                    }
                                    else {
                                        console.log(user);
                                        const firstName = user.first_name;
                                        document.getElementById("fullName").value = user.first_name + " " + user.last_name;
                                        document.getElementById("email").value = user.email;
                                        document.getElementById("studentID").value = user.utsa_id;
                                        document.getElementById("title").innerHTML = `Hello ${firstName}! Request a Tutor`;

                                        //get courses
                                        fetch("get_courses.php", {
                                            method: 'POST',
                                            headers:{
                                                "Content-Type": 'application/json'
                                            },
                                            body: JSON.stringify({userType: "student", id: user.utsa_id})
                                        })
                                            .then(response => response.json())
                                            .then(data =>{
                                                //able to retrieve the courses
                                                if(data.status === "success"){
                                                    console.log("success");
                                                    const courses = data.courses;
                                                    console.log(courses);

                                                    const courseSelectEl = document.getElementById("class");
                                                    for(var course of courses){
                                                        const optionElement = new Option(course.toString(), course.toString());
                                                        courseSelectEl.append(optionElement);
                                                    }
                                                }
                                                else{
                                                    //TODO: handle what happens when courses not found
                                                }
                                            })
                                            .catch(error => console.error('Error:', error));

                                    }
                                })
                        }
                    })
            }
        });
    </script>
</head>
<body>
    <div class="user-button">
        <div id="user-button"></div>
    </div>

    <div class="container">
        <h2 id="title">CS LAB Tutoring</h2>
        
        <!-- Form to capture student info -->
        <form action="submit_form.php" method="POST">

            <!-- Full name -->
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" readonly required>

            <!-- Student ID -->
            <label for="studentID">Student ID</label>
            <input type="text" id="studentID" name="studentID" readonly required>

            <!-- Email -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" readonly required>

            <!-- Dynamic Class dropdown with tutor details based on availability -->
            <label for="class">Select Class</label>
            <select id="class" name="class" required>
                <option value="">-- Select a Class --</option>
<!--                --><?php //foreach ($availableClasses as $class): ?>
<!--                    <option value="--><?php //echo htmlspecialchars($class['subject']); ?><!--">-->
<!--                        --><?php //echo htmlspecialchars($class['subject']) . " (Tutor: " . htmlspecialchars($class['tutor_name']) . ")"; ?>
<!--                    </option>-->
<!--                --><?php //endforeach; ?>
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

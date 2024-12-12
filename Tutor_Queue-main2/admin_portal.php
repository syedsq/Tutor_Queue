<?php
// Include the database connection
require 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <!-- <link href="./assets/style.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="./assets/main.css" type="text/css">
    <script async crossorigin="anonymous"
        data-clerk-publishable-key="pk_test_d2lsbGluZy1kaW5vc2F1ci05MS5jbGVyay5hY2NvdW50cy5kZXYk"
        src="https://willing-dinosaur-91.clerk.accounts.dev/npm/@clerk/clerk-js@latest/dist/clerk.browser.js"
        type="text/javascript"></script>
    <!-- <style>
        .button {
            background-color: #0c2340;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 10px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #173863;
        }
    </style> -->

    <script>
        window.addEventListener("load", async () => {
            await Clerk.load();

            // Redirect if no user is signed in
            if (!Clerk.user) {
                window.location.href = 'user-auth/signin.php';
            }
            else {
                const userButtonDiv = document.getElementById('user-button');
                const clerkUserId = Clerk.user.id;
                console.log(Clerk.user);
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
                        if (data.status === "success") {
                            const user = data.user;
                            //check if user exists on admin table
                            fetch("./user-auth/is_admin_user.php", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ utsa_id: user.utsa_id })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                    if (data.status !== "success") {
                                        document.location.href = "index.php";
                                    }
                                })
                        }
                        else {
                            console.log("Could not get user", data);
                        }
                    })
            }
        })
    </script>
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

    <div class="container-wrapper">
        <div class="top-line-image">
            <img src="assets/pics/san-antonio-skyline.png" alt="San Antonio Skyline">
        </div>

        <div class="user-button">
            <div id="user-button"></div>
        </div>

        <div class="container">
            <h2>Admin Portal</h2>

            <div class="button-container">
                <!-- Add Tutor Button -->
                <a href="manage_tutors.php" class="button">Add/Remove Tutor</a>

                <!-- View Results Button -->
                <a href="results.php" class="button">View Results</a>

                <!-- Upload CSV Button -->
                <a href="upload_csv.php" class="button">Upload CSV File</a>
            </div>
        </div>
    </div>

    <?php include('templates/footer.php'); ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Account</title>
    <script async crossorigin="anonymous"
        data-clerk-publishable-key="pk_test_d2lsbGluZy1kaW5vc2F1ci05MS5jbGVyay5hY2NvdW50cy5kZXYk"
        src="https://willing-dinosaur-91.clerk.accounts.dev/npm/@clerk/clerk-js@latest/dist/clerk.browser.js"
        type="text/javascript"></script>
    <!-- <link href="../assets/style.css" rel="stylesheet"> -->
    <link href="../assets/main.css" rel="stylesheet">
</head>

<body>
    <div class="hero-carousel">
        <div class="carousel-slide">
            <img src="../assets/pics/car.png" alt="Slide 1">
            <img src="../assets/pics/utsa main1.jpg" alt="Slide 2">
            <img src="../assets/pics/Roadrunner-Statue2.jpg" alt="Slide 3">
            <img src="../assets/pics/UTSA_Monument.jpg" alt="Slide 4">
            <img src="../assets/pics/data.jpg" alt="Slide 5">
        </div>
    </div>

    <div class="header">
        <img src="assets/UTSA.png">
    </div>

    <div class="container-wrapper">
        <div class="top-line-image">
            <img src="../assets/pics/san-antonio-skyline.png" alt="San Antonio Skyline">
        </div>

        <div class="container">
            <h2>Complete Profile</h2>

            <form action="" method="post">
                <!-- First name -->
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" readonly>

                <!-- Last name -->
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" readonly>

                <!-- Email -->
                <label for="email">Email</label>
                <input type="email" id="email" readonly>

                <!-- Student ID -->
                <label style="color: #F15A22" for="studentID">Student ID</label>
                <input type="text" id="studentID" required>

                <!-- Submit button -->
                <button onclick="submitForm()">Submit</button>
                <!--            <input type="submit" value="Submit" onclick="submitForm()">-->

            </form>

        </div>
    </div>

    <footer class="section grey darken-3 white-text center-align">
        <div>
            <p>Copyright © 2024 CS-UTSA | All Rights Reserved</p>
            <ul class="social-links">
                <li><a href="../tutor_dashboard.php" class="white-text">Tutor Dashboard / Privacy Policy</a></li>
                <li><a href="../admin_portal.php" class="white-text">Admin Portal / Terms of Service</a></li>
                <li><a href="../track_session.php" class="white-text">Track Session</a></li>
                <li><a href="../tutor_feedback.php" class="white-text">Tutor Feedback</a></li>
            </ul>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>

</html>

<script>
    window.addEventListener("load", async () => {
        await Clerk.load();
        if (Clerk.user) {
            console.log(Clerk.user.emailAddresses[0].emailAddress);
            //if abc123 missing -> form
            //else redirect to index.php
            const clerkUserId = Clerk.user.id;
            fetch('user_profile_complete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ user_id: clerkUserId })
            })
                .then(response => response.json())
                .then(data => {

                    if (data.status === "error") {
                        console.error('User record incomplete:', data);
                        document.getElementById("firstName").value = Clerk.user.firstName;
                        document.getElementById("lastName").value = Clerk.user.lastName;
                        document.getElementById("email").value = Clerk.user.emailAddresses[0];
                    }
                    else {
                        console.log('User record complete, msg: ', data);
                        window.location.href = "../index.php";
                    }

                })
                .catch(error => {

                });
        }
        else {
            window.location.href = "signin.php";
        }
    })

    function submitForm() {
        if (Clerk.user) {
            console.log('meow');
            const userData = {
                firstName: Clerk.user.firstName,
                lastName: Clerk.user.lastName,
                email: Clerk.user.emailAddresses[0].emailAddress,
                studentID: document.getElementById("studentID").value,
                clerkID: Clerk.user.id
            };
            fetch('add_user_db.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ userData })
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.log(error);
                })
        }

    }


</script>
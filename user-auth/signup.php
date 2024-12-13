<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Tracker Sign Up</title>
    <!-- Initialize Clerk with your Clerk Publishable Key -->
    <script
        async
        crossorigin="anonymous"
        data-clerk-publishable-key="pk_test_d2lsbGluZy1kaW5vc2F1ci05MS5jbGVyay5hY2NvdW50cy5kZXYk"
        src="https://willing-dinosaur-91.clerk.accounts.dev/npm/@clerk/clerk-js@latest/dist/clerk.browser.js"
        type="text/javascript"
    ></script>
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
    <img src="../assets/UTSA.png">
</div>

<div class="app" id="app">
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

<script>
    <?php
    require '../vendor/autoload.php';
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__.'/../');
    $dotenv->load();

    $basePath = $_ENV["BASE_PATH"];
    ?>

    const basePath = "<?php echo $basePath; ?>";
    console.log(basePath);
    window.addEventListener('load', async () => {
        await Clerk.load();

        if (Clerk.user) {
            window.location.href = '../index.php';
        } else {
            document.getElementById('app').innerHTML = `<div id="sign-up"></div>`;

            const signUpDiv = document.getElementById('sign-up')

            Clerk.mountSignUp(signUpDiv, {
                //register new user
                afterSignUpUrl: `${basePath}user-auth/new_user_flow.php`,
            });

        }
    });
</script>

</html>

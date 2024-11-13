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
    <link href="../assets/style.css" rel="stylesheet">
</head>
<body>
<div class="app" id="app">
</div>
</body>

<script>
    window.addEventListener('load', async () => {
        await Clerk.load();

        if (Clerk.user) {
            window.location.href = '../index.php';
        } else {
            document.getElementById('app').innerHTML = `<div id="sign-up"></div>`;

            const signUpDiv = document.getElementById('sign-up')

            Clerk.mountSignUp(signUpDiv, {
                //register new user
                afterSignUpUrl: 'tutor-queue/tutor-queue/new_user_flow.php',
            });

        }
    });
</script>

</html>

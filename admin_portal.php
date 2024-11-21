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
    <link href="./assets/style.css" rel="stylesheet">
    <script
            async
            crossorigin="anonymous"
            data-clerk-publishable-key="pk_test_d2lsbGluZy1kaW5vc2F1ci05MS5jbGVyay5hY2NvdW50cy5kZXYk"
            src="https://willing-dinosaur-91.clerk.accounts.dev/npm/@clerk/clerk-js@latest/dist/clerk.browser.js"
            type="text/javascript"
    ></script>
    <style>
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
    </style>

    <script>
        window.addEventListener("load", async () =>{
            await Clerk.load();

            // Redirect if no user is signed in
            if (!Clerk.user) {
                window.location.href = 'user-auth/signin.php';
            }
            else{
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
                    if(data.status === "success"){
                        const user = data.user;
                        //check if user exists on admin table
                        fetch("./user-auth/is_admin_user.php", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({utsa_id : user.utsa_id})
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log(data);
                                if(data.status !== "success"){
                                    document.location.href = "index.php";
                                }
                            })
                    }
                    else{
                        console.log("Could not get user", data);
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
    <div class="container">
        <h2>Admin Portal</h2>

        <!-- Add Tutor Button -->
        <a href="manage_tutors.php" class="button">Add/Remove Tutor</a>

        <!-- View Results Button -->
        <a href="results.php" class="button">View Results</a>
    </div>

</body>
</html>

<?php
// Start the session
session_start();

// Include database connection
require 'db_connect.php';

// Variable to hold error message
$error = "";

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $banner_id = $_POST['banner_id'];

    // Check if email and banner ID are not empty
    if (!empty($email) && !empty($banner_id)) {
        // Prepare SQL query to check if tutor exists
        $query = "SELECT * FROM tutors WHERE email = :email AND banner_id = :banner_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':banner_id', $banner_id);
        $stmt->execute();

        // Fetch the tutor record
        $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a tutor was found
        if ($tutor) {
            // Store tutor information in session
            $_SESSION['tutor_id'] = $tutor['id'];
            $_SESSION['tutor_name'] = $tutor['tutor_name'];

            // Redirect to tutor dashboard
            header('Location: tutor_dashboard.php');
            exit();
        } else {
            $error = "Invalid email or banner ID!";
        }
    } else {
        $error = "Please enter both email and banner ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #ff8200;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #e67300;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tutor Login</h2>

        <!-- Display error message if login fails -->
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Tutor Login Form -->
        <form method="POST" action="tutor_login.php">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="banner_id">Banner ID (Password)</label>
            <input type="text" id="banner_id" name="banner_id" required>

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>

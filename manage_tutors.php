<?php
// Include database connection
require 'db_connect.php';

// Variable to track if a new tutor was added successfully
$newTutorAdded = false;

// Fetch existing tutors for display
$query = "SELECT * FROM tutors";
$stmt = $conn->prepare($query);
$stmt->execute();
$tutors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle adding a tutor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_tutor'])) {
    $tutor_name = $_POST['tutor_name'];
    $email = $_POST['email'];
    $banner_id = $_POST['banner_id'];
    $subjects = implode(',', $_POST['subjects']); // Multiple subjects as comma-separated values

    // Ensure all fields are filled
    if (!empty($tutor_name) && !empty($email) && !empty($banner_id) && !empty($subjects)) {
        // Insert tutor info into tutors table
        $query = "INSERT INTO tutors (tutor_name, email, banner_id, subject) VALUES (:tutor_name, :email, :banner_id, :subjects)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':tutor_name', $tutor_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':banner_id', $banner_id);
        $stmt->bindParam(':subjects', $subjects);
        $stmt->execute();

        // Set the flag to trigger the pop-up
        $newTutorAdded = true;
    } else {
        echo "All fields are required!";
    }
}

// Handle removing a tutor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_tutor'])) {
    $tutor_id = $_POST['tutor_id'];
    $query = "DELETE FROM tutors WHERE id = :tutor_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':tutor_id', $tutor_id);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tutors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select {
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
        }

        button:hover {
            background-color: #e67300;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .remove-button {
            background-color: #d9534f;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .remove-button:hover {
            background-color: #c9302c;
        }
    </style>

    <!-- Add this JavaScript for pop-up -->
    <script>
        function showPopup() {
            alert("New Tutor Added Successfully!");
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Add Tutor</h2>
        <form method="POST" action="manage_tutors.php">
            <label for="tutor_name">Tutor Name:</label>
            <input type="text" id="tutor_name" name="tutor_name" required>

            <label for="email">Student Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="banner_id">Banner ID (Password):</label>
            <input type="text" id="banner_id" name="banner_id" required>

            <label for="subjects">Subjects (hold CTRL to select multiple):</label>
            <select id="subjects" name="subjects[]" multiple required>
                <option value="CS1063">CS1063 - Intro to Comp Programming I</option>
                <option value="CS1083">CS1083 - Prog I for Computer Scientists</option>
                <option value="CS1714">CS1714 - Computer Programming II</option>
                <option value="CS2073">CS2073 - Comp Prog w/ Egr Application</option>
                <option value="CS2113">CS2113 - Fundamentals of Object Oriented Programming</option>
                <option value="CS2124">CS2124 - Data Structures</option>
                <option value="CS2233">CS2233 - Discrete Math</option>
                <option value="CS3424">CS3424 - Systems Programming</option>
                <option value="CS3443">CS3443 - Application Programming</option>
                <option value="CS3723">CS3723 - Programming Languages</option>
                <option value="CS3843">CS3843 - Computer Organization</option>
            </select>

            <button type="submit" name="add_tutor">Add Tutor</button>
        </form>

        <h2>Existing Tutors</h2>
        <ul>
    <?php if (!empty($tutors)): ?>
        <?php foreach ($tutors as $tutor): ?>
            <li>
                <strong><?php echo htmlspecialchars($tutor['tutor_name']); ?></strong> - <?php echo htmlspecialchars($tutor['subject']); ?>
                <br>
                Email: <?php echo htmlspecialchars($tutor['email']); ?> <br>
                Banner ID: <?php echo htmlspecialchars($tutor['banner_id']); ?>
                
                <form method="POST" action="manage_tutors.php" style="display:inline;">
                    <input type="hidden" name="tutor_id" value="<?php echo $tutor['id']; ?>">
                    <button type="submit" class="remove-button" name="remove_tutor">Remove</button>
                </form>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No tutors found.</li>
    <?php endif; ?>
</ul>

    </div>

    <!-- Trigger the pop-up if a new tutor was added -->
    <?php if ($newTutorAdded): ?>
        <script>
            showPopup();
        </script>
    <?php endif; ?>
</body>
</html>

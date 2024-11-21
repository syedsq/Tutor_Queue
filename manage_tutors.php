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
    $abc123 = $_POST['abc123'];
    $subjects = implode(',', $_POST['subjects']); // Multiple subjects as comma-separated values

    // Ensure all fields are filled
    if (!empty($tutor_name) && !empty($email) && !empty($abc123) && !empty($subjects)) {
        // Insert tutor info into tutors table
        $query = "INSERT INTO tutors (tutor_name, email, utsa_id) VALUES (:tutor_name, :email, :abc123)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':tutor_name', $tutor_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':abc123', $abc123);
        $stmt->execute();

        //inserts the mappings for course -> tutor in tutor_courses
        $courseIds = explode(',', $subjects);
        foreach ($courseIds as $courseId) {
            $coursesQuery = "INSERT INTO tutor_courses (tutor_id, course_id) VALUES (:tutor_id, :course_id)";
            $stmt = $conn->prepare($coursesQuery);
            $stmt->bindParam(':tutor_id', $abc123);
            $stmt->bindParam(':course_id', $courseId);
            $stmt->execute();
        }

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

    $deleteQuery = "DELETE FROM tutor_courses WHERE tutor_id = :tutor_id";
    $stmt = $conn->prepare($deleteQuery);
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

            <label for="abc123">abc123:</label>
            <input type="text" id="abc123" name="abc123" required>

            <label for="subjects">Subjects (hold CTRL to select multiple):</label>
            <select id="subjects" name="subjects[]" multiple required>
                <?php
                    $coursesQuery = "SELECT * FROM courses";
                    $stmt = $conn->prepare($coursesQuery);
                    $stmt->execute();
                    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($courses as $course) {
                        echo "<option value='$course[course_id]'>$course[course_id] - $course[course_name]</option>";
                    }
                ?>
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
                abc123: <?php echo htmlspecialchars($tutor['utsa_id']); ?>
                
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

<?php
// Include the database connection
require 'db_connect.php';

// Define variables for error/success messages
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file is uploaded
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Allow only CSV files
        if ($fileExtension === 'csv') {
            // Process the file
            $handle = fopen($fileTmpPath, 'r');
            if ($handle !== false) {
                // Skip the header row
                fgetcsv($handle);

                // Insert each row into the database
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    // Extract values from CSV
                    $abc123 = $row[0];
                    $lastName = $row[1];
                    $firstName = $row[2];
                    $dept = $row[3];
                    $course = $row[4];
                    $section = $row[5];

                    // Insert into database (assuming a table named 'courses')
                    $stmt = $conn->prepare("INSERT INTO courses (abc123, last_name, first_name, dept, course, section) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('sssssi', $abc123, $lastName, $firstName, $dept, $course, $section);
                    $stmt->execute();
                }

                fclose($handle);
                $success = "CSV file uploaded and data successfully imported!";
            } else {
                $error = "Error opening the CSV file.";
            }
        } else {
            $error = "Only CSV files are allowed.";
        }
    } else {
        $error = "No file uploaded or there was an upload error.";
    }
}
?>

<?php
// include('templates/header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV File</title>
    <link rel="stylesheet" href="./assets/main.css" type="text/css">

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
        <div class="container">
            <h2>Upload CSV File</h2>

            <!-- Display Success/Error Messages -->
            <?php if ($success): ?>
                <div class="feedback success"><?= htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="feedback error"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- CSV Upload Form -->
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="csv_file" accept=".csv" required>
                <button type="submit" class="button">Upload</button>
            </form>
        </div>
    </div>

    <?php
    include('templates/footer.php');
    ?>
</body>

</html>
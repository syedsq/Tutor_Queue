<?php
// db_connect.php - Database connection file

$host = 'localhost'; // Assuming XAMPP defaults
$db = 'tutor_management'; // The name of the database
$user = 'root'; // Default XAMPP MySQL user
$pass = 'SeniorDesign123'; // XAMPP MySQL password (usually blank for default setup)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

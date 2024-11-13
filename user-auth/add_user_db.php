<?php /** @noinspection ALL */
// Include database connection
require '../db_connect.php';

// Retrieve the JSON payload from the request
$data = json_decode(file_get_contents('php://input'), true);

// Extract user data from the payload
$userData = $data['userData'] ?? null;

if ($userData) {
    // Prepare SQL statement to insert data into the users table
    $query = "
        INSERT INTO users (clerk_user_id, first_name, last_name, email, utsa_id)
        VALUES (:clerk_user_id, :first_name, :last_name, :email, :utsa_id)
        ON DUPLICATE KEY UPDATE
            first_name = VALUES(first_name),
            last_name = VALUES(last_name),
            email = VALUES(email),
            utsa_id = VALUES(utsa_id)
    ";

    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':clerk_user_id', $userData['clerkID']);
    $stmt->bindParam(':first_name', $userData['firstName']);
    $stmt->bindParam(':last_name', $userData['lastName']);
    $stmt->bindParam(':email', $userData['email']);
    $stmt->bindParam(':utsa_id', $userData['studentID']);

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User data inserted/updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert/update user data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user data provided']);
}


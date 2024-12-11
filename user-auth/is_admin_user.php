<?php
// Include database connection
require '../db_connect.php';

// Retrieve the utsa_id from the request (e.g., from POST or GET data)
$data = json_decode(file_get_contents('php://input'), true);
$utsaId = $data['utsa_id'] ?? null;

if ($utsaId != null) {
    // Prepare the SQL query to retrieve user by clerk_user_id
    $query = "SELECT * FROM admins WHERE utsa_id = :utsa_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':utsa_id', $utsaId);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User found, return user data as JSON
        echo json_encode(['status' => 'success', 'user' => $user]);
    } else {
        // No user found
        echo json_encode(['status' => 'error', 'message' => 'No admins found with given utsa id']);
    }
} else {
    // clerk_user_id not provided
    echo json_encode(['status' => 'error', 'message' => 'No utsa_id provided']);
}



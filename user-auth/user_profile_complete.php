<?php /** @noinspection SqlNoDataSourceInspection */
//check if user has an abc123 or if userid not found
require "../db_connect.php";

$data = json_decode(file_get_contents('php://input'), true);
$clerkUserId = $data['user_id'];

if ($clerkUserId) {
    // Prepare the SQL query to check if the record exists and if utsa_id is empty
    $query = "
        SELECT *
        FROM users
        WHERE clerk_user_id = :clerk_user_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':clerk_user_id', $clerkUserId);
    $stmt->execute();

    // Fetch the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Record found, check if utsa_id is empty
        if (empty($user['utsa_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Record found, utsa_id is empty', 'user' => $user]);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Record found, utsa_id is not empty', 'user' => $user]);
        }
    } else {
        // No record found
        echo json_encode(['status' => 'error', 'message' => 'No record found for the given clerk_user_id']);
    }
} else {
    // Clerk user ID not provided
    echo json_encode(['status' => 'error', 'message' => 'No clerk_user_id provided']);
}



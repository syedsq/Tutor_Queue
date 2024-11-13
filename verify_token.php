
<?php
require 'vendor/autoload.php';
require 'clerk.php';

header('Content-Type: application/json');

// Read the JSON input from the request
$input = json_decode(file_get_contents('php://input'), true);
$sessionId = isset($input['session_id']) ? $input['session_id'] : '';

if (!$sessionId) {
    echo json_encode(['valid' => false, 'error' => 'No session ID provided']);
    http_response_code(400);
    exit;
}

$sessionDetails = getSessionDetails($sessionId);

if ($sessionDetails && $sessionDetails['status'] === 'active') {
    echo json_encode([
        'valid' => true,
        'user_id' => $sessionDetails['user_id'],
        'client_id' => $sessionDetails['client_id'],
        'session_data' => $sessionDetails
    ]);
} else {
    echo json_encode(['valid' => false, 'error' => 'Invalid or inactive session']);
    http_response_code(401);
}
?>

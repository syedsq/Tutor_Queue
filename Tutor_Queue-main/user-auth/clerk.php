<?php
require 'vendor/autoload.php';
use Dotenv\Dotenv;

$retrieveSessionUrl = 'https://api.clerk.dev/v1/sessions/';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$secretKey = $_ENV["CLERK_SECRET_KEY"];
/**
 * Verifies the Clerk session by retrieving session details.
 *
 * @param string $sessionId The session ID to retrieve.
 * @return array|bool An array of session details if valid, false otherwise.
 */
function getSessionDetails($sessionId) {
    global $retrieveSessionUrl, $secretKey;

    if (empty($sessionId)) {
        return false;
    }

    $url = $retrieveSessionUrl . $sessionId;

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $secretKey,
        'Content-Type: application/json'
    ]);

    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close the cURL session
    curl_close($ch);

    // Check if the response is successful and decode the data
    if ($httpCode === 200) {
        $sessionData = json_decode($response, true);
        return $sessionData;
    } else {
        return false;
    }
}
?>

<?php
// delete_user.php

// Assuming you have a database connection established
require_once '../dbcon.php';

// Error handling for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the email to delete from POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if email is present in the data received
if (isset($data['email'])) {
    $email = $data['email'];

    try {
        // Prepare and execute the SQL statement to delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
        if ($stmt->execute([$email])) {
            // Check if deletion was successful
            if ($stmt->rowCount() > 0) {
                // Return success message
                echo json_encode(['message' => 'User deleted successfully']);
            } else {
                // Return error message if user not found
                echo json_encode(['error' => 'User not found or already deleted']);
            }
        } else {
            // Return error message if deletion failed
            echo json_encode(['error' => 'Failed to delete user']);
        }
    } catch (PDOException $e) {
        // Return error message for database errors
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Return error message if email parameter is missing
    echo json_encode(['error' => 'Email parameter is missing']);
}
?>

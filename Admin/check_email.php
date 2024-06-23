<?php
// check_email.php

// Assuming you have a database connection established
require_once '../dbcon.php'; // Adjust the path as per your file structure

// Get the email to check from the query string
$email = isset($_GET['email']) ? $_GET['email'] : '';

if (empty($email)) {
    echo json_encode(['error' => 'Email parameter is missing']);
    exit;
}

try {
    // Prepare and execute the SQL statement to check if email exists
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();

    // Check if email exists
    if ($count > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>

<?php
// delete_worker.php

// Include your database connection file
require_once '../dbcon.php';

// Assume you are receiving JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    // Handle error if JSON data is not received
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'No data received']);
    exit;
}

// Extract email from JSON
$email = isset($data['email']) ? trim($data['email']) : '';

// Delete the worker from the database
try {
    // Prepare SQL statement
    $sql = "DELETE FROM DeliveryPerson WHERE del_email = :email";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Execute the deletion
    $stmt->execute();

    // Respond with success message
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Worker deleted successfully']);
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

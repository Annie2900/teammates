<?php
// save_changes.php

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

// Extract data from JSON
$email = isset($data['email']) ? trim($data['email']) : '';
$firstname = isset($data['firstname']) ? trim($data['firstname']) : '';
$lastname = isset($data['lastname']) ? trim($data['lastname']) : '';
$phone = isset($data['phone']) ? trim($data['phone']) : '';
$is_banned = isset($data['is_banned']) ? (int) $data['is_banned'] : 0;

// Update the user information in the database
try {
    // Prepare SQL statement
    $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, phone = :phone, is_banned = :is_banned WHERE email = :email";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':is_banned', $is_banned, PDO::PARAM_INT);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Execute the update
    $stmt->execute();

    // Respond with success message
    header('Content-Type: application/json');
    echo json_encode(['message' => 'User information updated successfully']);
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

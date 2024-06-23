<?php
// save_changes_worker.php

require_once '../dbcon.php'; // Include your database connection file

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

// Update the worker information in the database
try {
    // Prepare SQL statement
    $sql = "UPDATE DeliveryPerson SET del_fname = :firstname, del_lname = :lastname WHERE del_email = :email";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Execute the update
    $stmt->execute();

    // Check if any rows were updated
    if ($stmt->rowCount() > 0) {
        // Respond with success message
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Worker information updated successfully']);
    } else {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['error' => 'Worker not found']);
    }
} catch (PDOException $e) {
    // Handle database errors
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

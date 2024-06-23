<?php
// save_new_user.php

// Assuming you have a database connection established
require_once '../dbcon.php'; // Adjust the path as per your file structure

// Retrieve JSON data from the request body
$data = json_decode(file_get_contents("php://input"));

// Validate JSON data
if (!$data) {
    echo json_encode(['error' => 'Invalid JSON data received']);
    exit;
}

// Extract data from JSON
$firstname = isset($data->firstname) ? trim($data->firstname) : '';
$lastname = isset($data->lastname) ? trim($data->lastname) : '';
$phone = isset($data->phone) ? trim($data->phone) : '';
$email = isset($data->email) ? trim($data->email) : '';
$password = isset($data->password) ? trim($data->password) : '';
$is_banned = isset($data->is_banned) ? intval($data->is_banned) : 0; // Ensure integer type
$active = isset($data->active) ? intval($data->active) : 1; // Ensure integer type

// Validate required fields
if (empty($firstname) || empty($lastname) || empty($phone) || empty($email) || empty($password)) {
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the SQL statement to insert new user
try {
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, phone, email, password, is_banned, active)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstname, $lastname, $phone, $email, $hashedPassword, $is_banned, $active]);

    echo json_encode(['message' => 'User created successfully']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to create user: ' . $e->getMessage()]);
}
?>

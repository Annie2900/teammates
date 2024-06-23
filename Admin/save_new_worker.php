<?php
// save_new_worker.php

require_once '../dbcon.php'; // Include your database connection file

// Ensure POST data is received and not empty
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data)) {
    echo json_encode(['error' => 'No data received']);
    exit;
}

// Extract data from JSON
$firstname = isset($data['firstname']) ? $data['firstname'] : '';
$lastname = isset($data['lastname']) ? $data['lastname'] : '';
$email = isset($data['email']) ? $data['email'] : '';
$password = isset($data['password']) ? $data['password'] : '';

// Validate required fields
if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

try {
    // Check if the email already exists
    $stmt = $conn->prepare('SELECT COUNT(*) AS count FROM DeliveryPerson WHERE del_email = ?');
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        echo json_encode(['error' => 'Email already registered']);
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Retrieve admin_id based on admin_email from session
    session_start();
    $admin_email = isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : '';
    
    if (empty($admin_email)) {
        echo json_encode(['error' => 'Admin email not found in session']);
        exit;
    }

    // Query to fetch admin_id based on admin_email
    $adminStmt = $conn->prepare('SELECT admin_id FROM Admin WHERE admin_email = ?');
    $adminStmt->execute([$admin_email]);
    $adminResult = $adminStmt->fetch(PDO::FETCH_ASSOC);

    if (!$adminResult) {
        echo json_encode(['error' => 'Admin not found in database']);
        exit;
    }

    $admin_id = $adminResult['admin_id'];

    // Insert the new delivery person into the database
    $insertStmt = $conn->prepare('INSERT INTO DeliveryPerson (del_fname, del_lname, del_password, del_email, admin_id) VALUES (?, ?, ?, ?, ?)');
    $insertStmt->execute([$firstname, $lastname, $hashedPassword, $email, $admin_id]);

    // Return success message
    echo json_encode(['message' => 'Delivery person created successfully']);
} catch (PDOException $e) {
    // Log database error
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Failed to create delivery person. Please try again.']);
} catch (Exception $e) {
    // Log unexpected error
    error_log('Unexpected error: ' . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Failed to create delivery person. Please try again.']);
}
?>

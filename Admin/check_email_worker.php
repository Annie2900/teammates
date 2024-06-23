<?php
// check_email_worker.php

require_once '../dbcon.php'; // Include your database connection file

// Check if email parameter exists
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    try {
        // Prepare SQL statement to count rows with matching email
        $stmt = $conn->prepare('SELECT COUNT(*) AS count FROM DeliveryPerson WHERE del_email = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return JSON response indicating if email exists
        header('Content-Type: application/json');
        echo json_encode(['exists' => ($result['count'] > 0)]);
    } catch (PDOException $e) {
        // Log database error
        error_log('Database error: ' . $e->getMessage());
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Log unexpected error
        error_log('Unexpected error: ' . $e->getMessage());
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Unexpected error: ' . $e->getMessage()]);
    }
} else {
    // Return error if email parameter is missing
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Email parameter is missing']);
}
?>

<?php
// get_worker_data.php

require_once '../dbcon.php'; // Include your database connection file

// Check if the email parameter is set
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    try {
        // Prepare SQL statement to fetch data
        $sql = "SELECT del_fname, del_lname, del_email FROM DeliveryPerson WHERE del_email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $worker = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($worker) {
            echo json_encode($worker);
        } else {
            echo json_encode([]);
        }
    } catch (PDOException $e) {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>

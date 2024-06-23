<?php
require_once '../dbcon.php';

// Check if the email parameter is set
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    try {
        $sql = "SELECT firstname, lastname, phone, email, password, is_banned FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode($user);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>

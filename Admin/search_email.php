<?php
require_once '../dbcon.php';

// Check if the query parameter is set
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    try {
        $sql = "SELECT email FROM users WHERE email LIKE :query";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['query' => '%' . $query . '%']);
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo json_encode($emails);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>

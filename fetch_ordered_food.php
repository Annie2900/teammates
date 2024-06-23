<?php
require_once 'dbcon.php';

header('Content-Type: application/json');

if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo json_encode(['error' => 'Order ID is missing']);
    http_response_code(400); // Bad Request
    exit();
}

$order_id = (int) $_GET['order_id'];

try {
    $sql = "SELECT f.food_name
            FROM OrderedFood fo
            INNER JOIN Food f ON fo.food_id = f.food_id
            WHERE fo.order_id = :order_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $orderedFood = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$orderedFood) {
        echo json_encode(['error' => 'No food found for this order']);
        http_response_code(404); // Not Found
        exit();
    }

    echo json_encode($orderedFood);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    http_response_code(500); // Internal Server Error
}

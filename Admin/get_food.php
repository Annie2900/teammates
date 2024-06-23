<?php
include '../dbcon.php'; // Ensure this path is correct for your project structure

if (isset($_GET['food_id'])) {
    $food_id = intval($_GET['food_id']);

    try {
        $stmt = $conn->prepare("SELECT food_category, food_name, food_price, food_quantity, food_desc FROM Food WHERE food_id = ?");
        $stmt->execute([$food_id]);
        $food_data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($food_data);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

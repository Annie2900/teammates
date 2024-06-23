<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    exit();
}

include '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_id = $_POST['food_select_offer'];
    $special_offer_price = $_POST['special_offer_price'];

    try {
        $stmt = $conn->prepare("UPDATE Food SET food_discount = :special_offer_price WHERE food_id = :food_id");
        $stmt->bindParam(':special_offer_price', $special_offer_price);
        $stmt->bindParam(':food_id', $food_id);
        $stmt->execute();
       	echo '<script>alert("Akció sikeresen hozzáadva!"); window.location.href = "https://teammates.stud.vts.su.ac.rs/proji/register/Admin/food.php";</script>';
        exit();
    } catch (PDOException $e) {
        echo "Hiba: " . $e->getMessage();
    }
}
?>

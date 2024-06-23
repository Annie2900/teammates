<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    exit();
}

include '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_id = $_POST['food_select_remove_offer'];

    try {
        $stmt = $conn->prepare("UPDATE Food SET food_discount = NULL WHERE food_id = :food_id");
        $stmt->bindParam(':food_id', $food_id);
        $stmt->execute();

        // JavaScript alert és átirányítás
        echo '<script>alert("Akció sikeresen eltávolítva!"); window.location.href = "https://teammates.stud.vts.su.ac.rs/proji/register/Admin/food.php";</script>';
        exit();
    } catch (PDOException $e) {
        echo "Hiba: " . $e->getMessage();
    }
}
?>

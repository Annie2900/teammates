<?php
session_start();
include 'dbcon.php';

// Token ellenőrzése
if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['rating_token']) {
    echo "Érvénytelen token!";
    exit();
}

// Fetch order_id and id_user from session
$order_id = $_SESSION['order_id'];
$id_user = $_SESSION['id_user'];

// Ha POST kérés érkezik, akkor feldolgozzuk az értékelést
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ellenőrizzük, hogy a futár értékelési adatok (delivery_rating) léteznek-e
    if (isset($_POST['delivery_rating'])) {
        $delivery_rating = $_POST['delivery_rating'];
        $delivery_comment = $_POST['delivery_comment'] ?? '';

        try {
            // Futár értékelése
            $sql = "INSERT INTO DeliveryRatingComment (del_id, id_user, del_rating, del_comment, order_id) 
                    SELECT o.del_id, :id_user, :delivery_rating, :delivery_comment, :order_id FROM Orders o WHERE o.order_id = :order_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'id_user' => $id_user,
                'delivery_rating' => $delivery_rating,
                'delivery_comment' => $delivery_comment,
                'order_id' => $order_id
            ]);

            // Session változók törlése csak sikeres művelet után
            unset($_SESSION['rating_token']);
            unset($_SESSION['order_id']);
            unset($_SESSION['id_user']);

            echo "Értékelés sikeresen elküldve!";
            exit();
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            echo "Hiba történt az értékelés beküldése közben.";
        }
    } else {
        echo "Hiányzó értékelési adatok!";
        exit();
    }
} else {
    // Ha nem POST kérés érkezik, akkor is töröljük a session változókat
    unset($_SESSION['rating_token']);
    unset($_SESSION['order_id']);
    unset($_SESSION['id_user']);

    echo "Érvénytelen kérés.";
    exit();
}
?>

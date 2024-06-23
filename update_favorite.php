<?php
session_start();
include 'dbcon.php';

if (isset($_SESSION['id_user']) && isset($_POST['food_id'])) {
    $food_id = $_POST['food_id'];
    // Ellenőrzés, hogy a felhasználó már kedvenceként mentette-e ezt az ételt
    $sql_check = "SELECT * FROM `Favorites` WHERE `food_id` = :food_id AND `user_id` = :user_id";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute(['food_id' => $food_id, 'user_id' => $_SESSION['id_user']]);
    $count = $stmt_check->rowCount();

    if ($count > 0) {
        // Már kedvenc, így törölni kell
        $sql_delete = "DELETE FROM `Favorites` WHERE `food_id` = :food_id AND `user_id` = :user_id";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->execute(['food_id' => $food_id, 'user_id' => $_SESSION['id_user']]);
        echo 'success'; // Sikeres törlés válasza
    } else {
        // Nem kedvenc, így hozzá kell adni
        $sql_add = "INSERT INTO `Favorites` (`food_id`, `user_id`) VALUES (:food_id, :user_id)";
        $stmt_add = $conn->prepare($sql_add);
        $stmt_add->execute(['food_id' => $food_id, 'user_id' => $_SESSION['id_user']]);
        echo 'success'; // Sikeres hozzáadás válasza
    }
} else {
    echo 'error'; // Hiba esetén válasz
}
?>

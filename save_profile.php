<?php
session_start();
require_once "dbcon.php";
require_once "functions_def.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id_user'];

    // Ellenőrizze, hogy a POST adatok megérkeztek-e
    if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['phone']) &&
        isset($_POST['city']) && isset($_POST['street'])) {

        // Ellenőrizze, hogy ténylegesen változtattak-e az adatokon
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $phone = trim($_POST['phone']);
        $city = trim($_POST['city']);
        $street = trim($_POST['street']);

        // Ellenőrizze, hogy legalább egy adat változott-e
        if ($firstname != '' || $lastname != '' || $phone != '' || $city != '' || $street != '') {

            // Frissítse az adatbázist
            $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, phone = :phone WHERE id_user = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Ellenőrizze, hogy van-e bejegyzés a UsersLocations táblában
            $sql = "SELECT * FROM UsersLocations WHERE id_user = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $userLocation = $stmt->fetch(PDO::FETCH_ASSOC);

            // Ha nincs, akkor beszúrjuk az adatokat
            if (!$userLocation) {
                $sql = "INSERT INTO UsersLocations (id_user, user_city, user_street) VALUES (:user_id, :city, :street)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':city', $city, PDO::PARAM_STR);
                $stmt->bindParam(':street', $street, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                // Ha van, akkor frissítjük
                $sql = "UPDATE UsersLocations SET user_city = :city, user_street = :street WHERE id_user = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':city', $city, PDO::PARAM_STR);
                $stmt->bindParam(':street', $street, PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Visszaküldjük az összes frissített adatot, beleértve az email címet is
            $sql = "SELECT email FROM users WHERE id_user = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $email = $stmt->fetchColumn();

            echo json_encode(array("success" => true, "email" => $email, "firstname" => $firstname, "lastname" => $lastname, "phone" => $phone, "city" => $city, "street" => $street));
            exit;
        } else {
            echo json_encode(array("success" => false, "message" => "No changes were made."));
            exit;
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Missing parameters."));
        exit;
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
    exit;
}
?>

<?php
session_start();
include 'dbcon.php';

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Ellenőrizzük, hogy POST kérés történt-e és vannak-e kiválasztott ételek
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['food_ids'])) {
    $foodIds = $_POST['food_ids'];
    $placeholders = rtrim(str_repeat('?,', count($foodIds)), ',');
    $sql = "SELECT food_id, food_name, food_desc, COALESCE(food_discount, food_price) as final_price FROM `Food` WHERE `food_id` IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($foodIds);
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ellenőrizzük, hogy minden kiválasztott étel megtalálható-e az adatbázisban
    if (count($foods) !== count($foodIds)) {
        echo "Néhány kiválasztott étel nem található az adatbázisban.";
        exit();
    }
} else {
    echo "Nincsenek kiválasztva ételek a rendeléshez.";
    exit();
}

$id_user = $_SESSION['id_user'];

// Felhasználói adatok lekérése, beleértve a telefonszámot
$sqlUser = "SELECT * FROM `users` WHERE `id_user` = :id_user";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->execute(['id_user' => $id_user]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Felhasználó helyadatainak lekérése
$sqlLocation = "SELECT * FROM `UsersLocations` WHERE `id_user` = :id_user";
$stmtLocation = $conn->prepare($sqlLocation);
$stmtLocation->execute(['id_user' => $id_user]);
$userLocation = $stmtLocation->fetch(PDO::FETCH_ASSOC);

// Ellenőrizzük, hogy a felhasználói vagy helyadatok léteznek-e
if (!$user || !$userLocation) {
    echo "Felhasználó vagy szállítási adatok nem találhatók.";
    exit();
}
// Check if the user is logged in
$loggedIn = isset($_SESSION['id_user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/food.css">
    <link rel="stylesheet" href="css/delivery.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style> body { background-image: url('images/wallpaper.jpg'); } </style>
	<script src="js/delivery.js"></script>
</head>
<body>
<nav>
    <div class="navbar navbar-expand-lg pt-4">
        <div class="container-fluid">
            <a href="#" class="brand text-decoration-none d-block d-lg-none fw-bold fs-1 ">LOGO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul id="nav-length" class="navbar-nav justify-content-between border-top border-2 text-center">
                    <li class="nav-item">
                        <a href="#" class="nav-link border-hover py-3 ">Kezdőlap</a>
                    </li>
                    <li class="nav-item">
                        <a href="food.php" class="nav-link border-hover py-3 ">Étlap</a>
                    </li>
                    <li class="nav-item">
                        <a href="aboutus.php" class="nav-link border-hover py-3 ">Rólunk</a>
                    </li>
                    <li class="nav-item">
                        <a href="review.php" class="nav-link border-hover py-3 ">Értékelések</a>
                    </li>
                     <li class="nav-item">
                        <?php if ($loggedIn): ?>
                            <a href="profile.php" id="sign-in" class="nav-link my-2 px-4 text-white">
                                <i class="bi bi-person-circle"></i>
                                Profil
                            </a>
                        <?php else: ?>
                            <a href="profile.php" id="sign-in" class="nav-link my-2 px-4 text-white">
                                <i class="bi bi-person-circle"></i>
                                Sign up
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Rendelés részletei</h2>
    <form action="order.php" method="POST">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Étel</th>
                <th>Leírás</th>
                <th>Ár (RSD)</th>
                <th>Mennyiség</th>
                <th>Összeg (RSD)</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $grandTotal = 0; // Kezdeti érték a végösszeg számításához
            foreach ($foods as $food):
                $foodId = $food['food_id'];
                $foodName = htmlspecialchars($food['food_name']);
                $foodDesc = htmlspecialchars($food['food_desc']);
                $pricePerUnit = $food['final_price']; // Az ár vagy az árengedmény értéke
                $quantity = isset($_POST['quantities'][$foodId]) ? intval($_POST['quantities'][$foodId]) : 1;
                $subtotal = $pricePerUnit * $quantity;
                $grandTotal += $subtotal;
                ?>
                <tr>
                    <td><?= $foodName ?></td>
                    <td><?= $foodDesc ?></td>
                    <td><?= number_format($pricePerUnit, 2) ?></td>
                    <td>
                        <input type="number" name="quantities[<?= $foodId ?>]" class="form-control quantity-input"
                               min="1" value="<?= $quantity ?>" data-price="<?= $pricePerUnit ?>">
                    </td>
                    <td class="total-price"><?= number_format($subtotal, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4" class="text-end">Végösszeg (RSD):</th>
                <th id="grand-total"><?= number_format($grandTotal, 2) ?></th>
            </tr>
            </tfoot>
        </table>
        <h3>Szállítási adatok</h3>
        <div class="mb-3">
            <label for="phone" class="form-label">Telefonszám</label>
            <input type="text" class="form-control" id="phone" name="phone"
                   value="<?= isset($user['phone']) ? htmlspecialchars($user['phone']) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Város</label>
            <input type="text" class="form-control" id="city" name="city"
                   value="<?= isset($userLocation['user_city']) ? htmlspecialchars($userLocation['user_city']) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="street" class="form-label">Utca</label>
            <input type="text" class="form-control" id="street" name="street"
                   value="<?= isset($userLocation['user_street']) ? htmlspecialchars($userLocation['user_street']) : '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Rendelés</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>


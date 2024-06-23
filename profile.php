<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "dbcon.php";
require_once "functions_def.php";

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['id_user'];

try {
    $sql = "SELECT u.*, ul.user_city, ul.user_street FROM users u LEFT JOIN UsersLocations ul ON u.id_user = ul.id_user WHERE u.id_user = :id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header('Location: index.php');
        exit();
    }

    // Kedvenc ételek lekérdezése
    $sql = "SELECT f.food_id, f.food_name, f.food_price FROM Favourite fav
            JOIN Food f ON fav.food_id = f.food_id
            WHERE fav.id_user = :id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Aktuális rendelések lekérdezése az Orders táblából
    $sql = "SELECT order_id, order_date, order_city, order_street, order_status, order_price 
            FROM Orders
            WHERE id_user = :id_user AND order_status != 2";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Hiba történt: " . $e->getMessage();
    exit();
}
?>

<!doctype html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Felhasználói profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/profile.css">
	<script src="js/profile.js"></script>
</head>
<body>

<nav>
    <div class="navbar navbar-expand-lg pt-4">
        <div class="container-fluid">
            <a href="#" class="brand text-decoration-none d-block d-lg-none fw-bold fs-1 ">LOGO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul id="nav-length" class="navbar-nav justify-content-between border-top border-2 text-center">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link border-hover py-3 ">Kezdőlap</a>
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
                        <a href="logout.php" id="sign-in" class="nav-link my-2 px-4 text-white">
                            <i class="bi bi-person-circle"></i>
                            Kijelentkezés
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    Felhasználói információk
                </div>
                <div class="card-body">
    <p><strong>Név:</strong> <?= htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
    <p><strong>Telefonszám:</strong> <?= htmlspecialchars($user['phone'] ?? '') ?></p>
    <div id="cityStreetInfo">
        <p><strong>Város:</strong> <?= htmlspecialchars($user['user_city'] ?? '') ?></p>
        <p><strong>Utca:</strong> <?= htmlspecialchars($user['user_street'] ?? '') ?></p>
    </div>
    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
        Profil szerkesztése
    </button>
</div>

            </div>
        </div>
    </div>
</div>

<?php if (!empty($favorites)): ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        Kedvenc ételek
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($favorites as $favorite): ?>
                                <li class="list-group-item">
                                    <?= htmlspecialchars($favorite['food_name']) ?> - <?= htmlspecialchars($favorite['food_price']) ?> RSD
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <form action="delivery.php" method="post"> <!-- Módosítás: delivery.php-re irányítjuk az adatokat -->
                            <?php foreach ($favorites as $favorite): ?>
                                <input type="hidden" name="food_ids[]" value="<?= $favorite['food_id'] ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-primary mt-3">Rendelés</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Aktuális rendelések -->
<?php if (!empty($orders)): ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        Aktuális rendelések
                    </div>
                    <div class="card-body">
                        <?php foreach ($orders as $order): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p><strong>Rendelési dátum:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
                                    <p><strong>Város:</strong> <?= htmlspecialchars($order['order_city']) ?></p>
                                    <p><strong>Utca:</strong> <?= htmlspecialchars($order['order_street']) ?></p>
   
                                    <p><strong>Ár:</strong> <?= htmlspecialchars($order['order_price']) ?> RSD</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Profil szerkesztése modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editProfileForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Profil szerkesztése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
                </div>
                <div class="modal-body">
    <div class="mb-3">
        <label for="firstName" class="form-label">Keresztnév</label>
        <input type="text" class="form-control" id="firstName" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="lastName" class="form-label">Vezetéknév</label>
        <input type="text" class="form-control" id="lastName" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Telefonszám</label>
        <input type="text" class="form-control" id="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="city" class="form-label">Város</label>
        <input type="text" class="form-control" id="city" value="<?= htmlspecialchars($user['user_city'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="street" class="form-label">Utca</label>
        <input type="text" class="form-control" id="street" value="<?= htmlspecialchars($user['user_street'] ?? '') ?>">
    </div>
</div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
                    <button type="submit" class="btn btn-primary">Változások mentése</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


</body>
</html>


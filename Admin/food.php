<?php
session_start();

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    exit();
}

include '../dbcon.php';

// Lekérjük a meglévő ételeket a legördülő menühöz
$food_items = [];
try {
    $stmt = $conn->query("SELECT food_id, food_name FROM Food");
    $food_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Hiba: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Menü</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/food.css">
	<script src="js/food.js"></script>
</head>
<body background="../images/wallpaper.jpg">
<nav>
    <div class="navbar navbar-expand-lg pt-4">
        <div class="container-fluid">
            <a href="menu.php" class="brand text-decoration-none d-block d-lg-none fw-bold fs-1 ">Menü</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul id="nav-length" class="navbar-nav justify-content-between border-top border-2 text-center">
                    <li class="nav-item">
                        <a href="admin.php" class="nav-link border-hover py-3 ">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a href="user.php" class="nav-link border-hover py-3 ">Felhasználó</a>
                    </li>
                    <li class="nav-item">
                        <a href="worker.php" class="nav-link border-hover py-3 ">Munkás</a>
                    </li>
                    <li class="nav-item">
                        <a href="food.php" class="nav-link border-hover py-3 ">Étel</a>
                    </li>
                    <li class="nav-item">
                        <a href="order.php" class="nav-link border-hover py-3 ">Rendelések</a>
                    </li>
					<li class="nav-item">
                            <a href="comments.php" class="nav-link border-hover py-3 ">Üzenet </a>
                    </li>
                    <li class="nav-item">
                        <form action="logout.php" method="post" style="text-align: right;">
                            <button type="submit" name="logout" class="logout-btn nav-link my-2 px-4 text-white">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="row">
        <!-- Add New Food Form -->
        <div class="col-md-4">
            <h2>Új Étel Hozzáadása</h2>
            <form method="post" action="add_food.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="food_category" class="form-label">Étel Kategória</label>
                    <input type="text" class="form-control" id="food_category" name="food_category" required>
                </div>
                <div class="mb-3">
                    <label for="food_name" class="form-label">Étel Neve</label>
                    <input type="text" class="form-control" id="food_name" name="food_name" required>
                </div>
                <div class="mb-3">
                    <label for="food_price" class="form-label">Étel Ára</label>
                    <input type="number" step="0.01" class="form-control" id="food_price" name="food_price" required>
                </div>
                <div class="mb-3">
                    <label for="food_quantity" class="form-label">Étel Mennyisége</label>
                    <input type="text" class="form-control" id="food_quantity" name="food_quantity" required>
                </div>
                <div class="mb-3">
                    <label for="food_desc" class="form-label">Étel Leírása</label>
                    <textarea class="form-control" id="food_desc" name="food_desc" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="alias">Alias:</label>
                    <input type="text" name="alias" id="alias" class="form-control"> <br>
                    <label for="if">
                        <img src="upload.png" alt="upload" width="50" title="Kép kiválasztása">
                    </label>
                    <input type="file" name="file" id="if" class="form-control"><br><br>
                </div>
                <button type="submit" class="btn btn-danger btn-custom">Étel Hozzáadása</button>

            </form>
        </div>
 <!-- Edit Food Form -->
    <div class="col-md-4">
        <h2>Étel Szerkesztése</h2>
        <form method="post" action="edit_food.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="food_select" class="form-label">Válasszon ételt</label>
                <select class="form-select" id="food_select" name="food_select" onchange="fetchFoodData(this.value)" required>
                    <option value="">Kérem válasszon</option>
                    <?php foreach ($food_items as $item): ?>
                        <option value="<?= $item['food_id'] ?>"><?= htmlspecialchars($item['food_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="edit_food_category" class="form-label">Étel Kategória</label>
                <input type="text" class="form-control" id="edit_food_category" name="food_category" required>
            </div>
            <div class="mb-3">
                <label for="edit_food_name" class="form-label">Étel Neve</label>
                <input type="text" class="form-control" id="edit_food_name" name="food_name" required>
            </div>
            <div class="mb-3">
                <label for="edit_food_price" class="form-label">Étel Ára</label>
                <input type="number" step="0.01" class="form-control" id="edit_food_price" name="food_price" required>
            </div>
            <div class="mb-3">
                <label for="edit_food_quantity" class="form-label">Étel Mennyisége</label>
                <input type="text" class="form-control" id="edit_food_quantity" name="food_quantity" required>
            </div>
            <div class="mb-3">
                <label for="edit_food_desc" class="form-label">Étel Leírása</label>
                <textarea class="form-control" id="edit_food_desc" name="food_desc" required></textarea>
            </div>
            <div class="mb-3">
                <label for="edit_alias">Alias:</label>
                <input type="text" name="alias" id="edit_alias" class="form-control"> <br>
                <label for="edit_if">
                    <img src="upload.png" alt="upload" width="50" title="Kép kiválasztása">
                </label>
                <input type="file" name="file" id="edit_if" class="form-control"><br><br>
            </div>
            <button type="submit" class="btn btn-danger btn-custom">Változtatások Mentése</button>
        </form>
    </div>

    <!-- Add Special Offer Form -->
    <div class="col-md-4">
        <h2>Akció Hozzáadása</h2>
        <form method="post" action="add_special_offer.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="food_select_offer" class="form-label">Válasszon ételt</label>
                <select class="form-select" id="food_select_offer" name="food_select_offer" required>
                    <option value="">Kérem válasszon</option>
                    <?php foreach ($food_items as $item): ?>
                        <option value="<?= $item['food_id'] ?>"><?= htmlspecialchars($item['food_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="special_offer_price" class="form-label">Akciós Ár</label>
                <input type="number" step="0.01" class="form-control" id="special_offer_price" name="special_offer_price" required>
            </div>
            <button type="submit" class="btn btn-danger btn-custom">Akció Hozzáadása</button>
        </form>
    </div>

    <!-- Remove Special Offer Form -->
    <div class="col-md-4">
        <h2>Akció Törlése</h2>
        <form method="post" action="remove_special_offer.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="food_select_remove_offer" class="form-label">Válasszon ételt</label>
                <select class="form-select" id="food_select_remove_offer" name="food_select_remove_offer" required>
                    <option value="">Kérem válasszon</option>
                    <?php foreach ($food_items as $item): ?>
                        <option value="<?= $item['food_id'] ?>"><?= htmlspecialchars($item['food_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-danger btn-custom">Akció Törlése</button>
        </form>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php
session_start();
include 'dbcon.php';


// Check if there is a success message from order.php and display it
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear the message from session after displaying

// Check if the message is set and not empty
if (!empty($message)) {
    echo "<script>alert('{$message}');</script>";
}

// Keresési logika
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
if ($searchTerm) {
    $sql = "SELECT * FROM `Food` WHERE `food_name` LIKE :searchTerm";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['searchTerm' => "%$searchTerm%"]);
} else {
    $sql = "SELECT * FROM `Food`";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kedvenc étel hozzáadása vagy eltávolítása
if (isset($_POST['favorite_food_id'])) {
    $foodId = $_POST['favorite_food_id'];
    $userId = $_SESSION['id_user'];

    // Ellenőrzés, hogy az étel már kedvenc-e
    $sql_check_favorite = "SELECT * FROM `Favourite` WHERE `food_id` = :food_id AND `id_user` = :id_user";
    $stmt_check_favorite = $conn->prepare($sql_check_favorite);
    $stmt_check_favorite->execute(['food_id' => $foodId, 'id_user' => $userId]);

    if ($stmt_check_favorite->rowCount() === 0) {
        // Ha még nem kedvenc, hozzáadás
        $sql_add_favorite = "INSERT INTO `Favourite` (`food_id`, `id_user`) VALUES (:food_id, :id_user)";
        $stmt_add_favorite = $conn->prepare($sql_add_favorite);
        $stmt_add_favorite->execute(['food_id' => $foodId, 'id_user' => $userId]);

       // $_SESSION['message'] = 'success'; // Set success message
        //echo 'success';
        exit;
    } else {
        // Ha már kedvenc, törlés
        $sql_remove_favorite = "DELETE FROM `Favourite` WHERE `food_id` = :food_id AND `id_user` = :id_user";
        $stmt_remove_favorite = $conn->prepare($sql_remove_favorite);
        $stmt_remove_favorite->execute(['food_id' => $foodId, 'id_user' => $userId]);

       // $_SESSION['message'] = 'removed'; // Set removed message
       // echo 'removed';
        exit;
    }
}


// Keresési logika
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';

if ($searchTerm && !$category) {
    // Search by food name only
    $sql = "SELECT * FROM `Food` WHERE `food_name` LIKE :searchTerm";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['searchTerm' => "%$searchTerm%"]);
} elseif (!$searchTerm && $category) {
    // Filter by category only
    $sql = "SELECT * FROM `Food` WHERE `food_category` = :category";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['category' => $category]);
} elseif ($searchTerm && $category) {
    // Search by food name and filter by category
    $sql = "SELECT * FROM `Food` WHERE `food_name` LIKE :searchTerm AND `food_category` = :category";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['searchTerm' => "%$searchTerm%", 'category' => $category]);
} else {
    // No search or category filter, fetch all
    $sql = "SELECT * FROM `Food`";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/food.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <script src="js/scripts.js"></script>
	<link rel="stylesheet" href="css/food2.css">
	<script src="js/food.js"></script>
    <title>Étlap</title>
  
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
                        <a href="index.php" class="nav-link border-hover py-3">Kezdőlap</a>
                    </li>
                    <li class="nav-item">
                        <a href="food.php" class="nav-link border-hover py-3">Étlap</a>
                    </li>
                    <li class="nav-item">
                        <a href="aboutus.php" class="nav-link border-hover py-3">Rólunk</a>
                    </li>
                    <li class="nav-item">
                        <a href="review.php" class="nav-link border-hover py-3">Értékelések</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['id_user'])): ?>
                            <a href="profile.php" id="sign-in" class="nav-link my-2 px-4 text-white">
                                <i class="bi bi-person-circle"></i>
                                Profil
                            </a>
                        <?php else: ?>
                            <a href="profile.php" id="sign-in" class="nav-link my-2 px-4 text-white">
                                <i class="bi bi-person-circle"></i>
                                Sign Up
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
       <div class="container mt-5">
    <form method="POST" class="mb-4">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="searchTerm" placeholder="Keresés étel neve alapján">
            <button type="submit" class="btn btn-primary">Keresés</button>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" for="categorySelect">Kategória:</label>
            <select class="form-select" id="categorySelect" name="category">
                <option value="">Összes kategória</option>
                <?php
                // Fetch all distinct food categories from the database
                $sql_categories = "SELECT DISTINCT food_category FROM `Food`";
                $stmt_categories = $conn->prepare($sql_categories);
                $stmt_categories->execute();
                $categories = $stmt_categories->fetchAll(PDO::FETCH_COLUMN);
                
                // Output each category as an option in the select dropdown
                foreach ($categories as $category) {
                    echo "<option value='{$category}'>{$category}</option>";
                }
                ?>
            </select>
        </div>
    </form>
                     
<div class="container mt-5">
    <form id="orderForm" action="delivery.php" method="POST">
        <?php foreach ($result as $row): ?>
    <div class="card mb-4 shadow-sm position-relative">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="uploadedImages/<?php echo htmlspecialchars($row['food_img'] ?: 'default.jpg'); ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($row['food_name']); ?>">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['food_name']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($row['food_desc']); ?></p>
                    <?php if (!empty($row['food_discount'])): ?>
                        <p class="card-text" style="color: red; text-decoration: line-through;"><?php echo htmlspecialchars($row['food_price']); ?> RSD</p>
                        <p class="card-text"><?php echo htmlspecialchars($row['food_discount']); ?> RSD (kedvezményes ár)</p>
                    <?php else: ?>
                        <p class="card-text"><?php echo htmlspecialchars($row['food_price']); ?> RSD</p>
                    <?php endif; ?>

                    <!-- Order checkbox (only if logged in) -->
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <input type="checkbox" name="food_ids[]" value="<?php echo $row['food_id']; ?>"> Rendelés
                    <?php endif; ?>

                    <!-- Kedvenc jelölő -->
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <?php
                        $is_favorite = false; // Assuming it's not a favorite by default
                        // Check if it's already a favorite
                        $sql_check_favorite = "SELECT * FROM `Favourite` WHERE `food_id` = :food_id AND `id_user` = :id_user";
                        $stmt_check_favorite = $conn->prepare($sql_check_favorite);
                        $stmt_check_favorite->execute(['food_id' => $row['food_id'], 'id_user' => $_SESSION['id_user']]);
                        if ($stmt_check_favorite->rowCount() > 0) {
                            $is_favorite = true;
                        }
                        ?>
                        <span class="favorite-star <?php echo $is_favorite ? 'selected' : ''; ?>" data-food-id="<?php echo $row['food_id']; ?>" onclick="toggleFavorite(<?php echo $row['food_id']; ?>)">
                            <i class="bi bi-star-fill"></i>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


        <!-- Kosárba helyezés gomb (only if logged in) -->
        <?php if (isset($_SESSION['id_user'])): ?>
            <button type="submit" class="btn btn-primary">Kosárba helyezés</button>
        <?php endif; ?>
  </form>
</div>

<!-- Popup for success or removed messages -->
<div id="popup" class="popup">
    <?php if (isset($_SESSION['message'])): ?>
        <?php
        $message = $_SESSION['message'];
        if ($message === 'success') {
            echo 'Kedvenc étel hozzáadva!';
        } elseif ($message === 'removed') {
            echo 'Kedvenc étel eltávolítva!';
        }
        ?>
        <script>
            showPopupMessage("<?php echo $message === 'success' ? 'Kedvenc étel hozzáadva!' : 'Kedvenc étel eltávolítva!'; ?>");
        </script>
        <?php unset($_SESSION['message']); ?> <!-- Clear the message after displaying -->
    <?php endif; ?>
</div>

<!-- Szükséges JavaScript könyvek -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
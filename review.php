<?php
session_start();
require_once 'dbcon.php';

// Check if user is logged in (you may adjust this based on your session handling)
$loggedIn = isset($_SESSION['id_user']);

// Fetch data from RatingComment table
$sql = "SELECT rc.food_rating, rc.food_comment, rc.order_id, o.order_date
        FROM Ratingcomment rc
        INNER JOIN Orders o ON rc.order_id = o.order_id
        ORDER BY o.order_date DESC"; // Example query to fetch ratings with order date

$stmt = $conn->query($sql);

// Fetch all rows into an associative array
$ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Értékelések</title>
    <!-- Include your CSS and JS files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/review.css">
    <script src="js/scripts.js"></script>
	<script src="js/review.js"></script>
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
                        <?php if ($loggedIn): ?>
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
    <h2 class="mb-4">Értékelések</h2>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-bordered-custom">
            <thead>
                <tr>
                    <th>Étel értékelése</th>
                    <th>Étel észrevételei</th>
                    <th>Rendelés dátuma</th>
                    <th>Gomb</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ratings as $rating): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rating['food_rating']); ?></td>
                    <td><?php echo htmlspecialchars($rating['food_comment']); ?></td>
                    <td><?php echo htmlspecialchars($rating['order_date']); ?></td>
                    <td><button class="btn btn-orange" onclick="openModal(<?php echo $rating['order_id']; ?>)">Megtekint</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modal-body"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

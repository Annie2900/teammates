<?php
session_start();
require_once 'dbcon.php';
require_once 'functions_def.php';

// Check if there are any error or success messages
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;

// Clear session messages to avoid displaying them multiple times
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

// Check if the user is logged in
$loggedIn = isset($_SESSION['id_user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <script src="js/scripts.js"></script>
	<script src="js/contact.js"></script>
    <title>Document</title>
</head>
<body background="images/wallpaper.jpg">
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
                                Sign up
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<main id="body-content">
    <div class="d-flex position-fixed">
        <div class="d-flex flex-column icon-container p-2" id="socialWrap">
            <i class="bi bi-youtube icon-height my-3"></i>
            <i class="bi bi-facebook icon-height my-3"></i>
            <i class="bi bi-instagram icon-height my-3"></i>
            <i class="bi bi-twitter icon-height my-3"></i>
        </div>
    </div>
    <div class="container text-white w-75">
        <p class="fs-2 fw-light m-0 mt-lg-5">Teammates</p>
        <h1 class="ml3 display-2 fw-bold mb-5">Éhes vagy és nincs otthon semmi?
            <h2 class="letters">Itt a helyed!</h2>
        </h1>
        <p class="w-50 fs-5 mb-5">
            Nálunk mindent megtalász amire csak vágysz! Prémium mindőségű ételek az ajtód előtt akár egy kattintással.
        </p>
        <button type="button" class="btn border text-white px-4 rounded-4 me-3 mb-5" onclick="showContactInfo()">Elérhetőségeink</button>
        <?php if (!$loggedIn): ?>
        <button type="button" class="btn icon-container text-white px-4 rounded-4 mb-5" onclick="window.location.href='login.php'">Csatlakozz!</button>
        </button>
        <?php endif; ?>  
        <div class="pos">
            <div class="row">
                <div class="col-4 icon-container">
                    <div class="desc p-3">
                        <p>Csatlakozzon és ...</p>
                        <p>Válasszon minőségi ételeket éttermünk változatos kínálatából!</p>
                    </div>
                </div>
                <div class="col-4 p-0">
                    <div class="desc p-3 bg-secondary">
                        <p>Csak is ..</p>
                        <p>Friss alapanyagokat használunk, hogy minden ízlést kielégítsünk.</p>
                    </div>
                </div>
                <div class="col-4 p-0">
                    <div class="desc p-3 dark-background">
                        <p>Futáraink elkötelezettek ...</p>
                        <p>Ezért mindig frissen szállítják az ételt Önnek, hogy frissen kapja meg a rendelését.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php
// Display error message if there's any
if ($error_message) {
    echo "<script>alert('$error_message');</script>";
}

// Display success message if there's any
if ($success_message) {
    echo "<script>alert('$success_message');</script>";
}
?>
</body>
</html>

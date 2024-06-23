<?php
session_start();

// Ellenőrizzük, hogy be van-e jelentkezve az admin
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Menü - Kiszállítók</title>
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
    <link rel="stylesheet" href="css/user.css">
    <script src="js/worker.js"></script>
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
    <a href="#" onclick="showPopupForm()">Új kiszállító hozzáadása</a>
    <h2>Keresés kiszállító e-mail alapján</h2>
    <form>
        <div class="form-group">
            <label for="workerEmail">Kiszállító E-mail:</label>
            <input type="text" id="workerEmail" name="workerEmail" class="form-control" onkeyup="searchEmail()">
            <ul id="emailList" class="list-group mt-2"></ul>
        </div>
    </form>
    <!-- Pop-up ablak az új kiszállító létrehozásához -->
    <div id="popupForm" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopupForm()">&times;</span>
            <h2>Új Kiszállító Létrehozása</h2>
            <div class="form-group">
                <label for="newDelFirstname">Keresztnév:</label>
                <input type="text" id="newDelFirstname" name="newDelFirstname" class="form-control">
            </div>
            <div class="form-group">
                <label for="newDelLastname">Vezetéknév:</label>
                <input type="text" id="newDelLastname" name="newDelLastname" class="form-control">
            </div>
            <div class="form-group">
                <label for="newDelEmail">E-mail:</label>
                <input type="email" id="newDelEmail" name="newDelEmail" class="form-control">
            </div>
            <div class="form-group">
                <label for="newDelPassword">Jelszó:</label>
                <input type="password" id="newDelPassword" name="newDelPassword" class="form-control">
            </div>
            <button type="button" class="button" onclick="createNewWorker()">Kiszállító Létrehozása</button>
        </div>
    </div>
    <!-- Pop-up ablak vége -->

    <form id="workerForm" class="mt-4" style="display: none;">
        <div class="form-group">
            <label for="delFirstname">Keresztnév:</label>
            <input type="text" id="delFirstname" name="delFirstname" class="form-control">
        </div>
        <div class="form-group">
            <label for="delLastname">Vezetéknév:</label>
            <input type="text" id="delLastname" name="delLastname" class="form-control">
        </div>
        <div class="form-group">
            <label for="delEmail">E-mail:</label>
            <input type="text" id="delEmail" name="delEmail" class="form-control" readonly>
        </div>
        <button type="button" class="btn btn-primary" onclick="saveWorkerChanges()">Mentés</button>
        <button type="button" class="btn btn-danger me-2" onclick="deleteWorker()">Törlés</button>
    </form>
</div>
</body>
</html>

<?php
session_start();
require_once '../dbcon.php';

// Redirect to login if not logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    exit();
}

// Insert new admin into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement to insert new admin
    $stmt = $conn->prepare("INSERT INTO Admin (admin_email, admin_password) VALUES (:email, :password)");
    $stmt->execute(['email' => $email, 'password' => $hashed_password]);

    // Redirect to a success page or refresh current page
    header("Location: admin.php"); // You can redirect to another page if needed
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Menu</title>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
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
	

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                Új admin hozzáadása
            </div>
            <div class="card-body">
                <form action="admin.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email cím</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Jelszó</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="submit" class="logout-btn nav-link my-2  text-white">Hozzáadás</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

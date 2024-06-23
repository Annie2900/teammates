<?php
session_start();
include 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM DeliveryPerson WHERE del_email = :email AND del_password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $password]);
    $deliveryPerson = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($deliveryPerson) {
        $_SESSION['del_id'] = $deliveryPerson['del_id'];
        header("Location: work.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Person Login</title>
    <link rel="stylesheet" href="css/work_login.css">
</head>
 <style>
        body {
            background-image: url('images/wallpaper.jpg');
            background-size: cover;
            background-attachment: fixed;
        }
    </style>
<body>
<div class="login-container">
    <h2>Futár bejelentkezése</h2>
    <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
    <form action="work_login.php" method="POST">
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="password">Jelszó:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Bejelentkezés</button>
    </form>
</div>
</body>
</html>

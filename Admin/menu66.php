<?php
session_start();

// Ellenőrizze, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['login_email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['login_email'];
unset($_SESSION['login_email']); // Törölje az e-mail session változót

// Ellenőrizze, hogy a form elküldve-e
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secret_code = $_POST['secret_code'];

    // Ellenőrizze, hogy a megadott kód megegyezik-e az adatbázisban tárolttal
    $stmt = $conn->prepare("SELECT * FROM Admin WHERE admin_email = :email AND secret = :secret");
    $stmt->execute(['email' => $email, 'secret' => $secret_code]);
    $admin = $stmt->fetch();

    if ($admin) {
        // Sikeres bejelentkezés
        // Ide jön az oldal tartalma, amit meg szeretne jeleníteni a felhasználónak

        echo "Sikeres bejelentkezés! Üdv, {$admin['admin_name']}.";
    } else {
        $error = "Hibás bejelentkezési kód.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menü</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="menu-container">
        <h2>Bejelentkezési kód megadása</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="menu66.php" method="post">
            <div>
                <label for="secret_code">Bejelentkezési kód:</label>
                <input type="text" id="secret_code" name="secret_code" required>
            </div>
            <button type="submit">Bejelentkezés</button>
        </form>
    </div>
</body>
</html>

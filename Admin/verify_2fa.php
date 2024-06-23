<?php
session_start();
require_once '../dbcon.php'; // Az adatbázis kapcsolat fájl elérési útja
require_once '../vendor/autoload.php'; // Composer autoload

use RobThree\Auth\TwoFactorAuth;

// Ellenőrizzük, hogy van-e bejelentkezett felhasználó
if (!isset($_SESSION['admin_email']) || !isset($_SESSION['2fa_secret'])) {
    header("Location: index.php");
    exit();
}

// Ha POST kérés érkezett és a "verify" gombra kattintottak
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    $tfa = new TwoFactorAuth();

    // Ellenőrizzük a beírt 2FA kódot
    $isValid = $tfa->verifyCode($_SESSION['2fa_secret'], $_POST['2fa_code']);

    if ($isValid) {
        // Ha a kód helyes, bejelentkeztetjük a felhasználót
        $_SESSION['authenticated'] = true;
        unset($_SESSION['2fa_secret']); // Törljük a használt 2FA titkos kódot a session-ből

        // Ide irányítsd a felhasználót az alkalmazás megfelelő részére (pl. admin felület)
        header("Location: menu.php");
        exit();
    } else {
        // Ha a kód nem helyes, hibát jelezünk
        $error = "Hibás vagy lejárt ellenőrző kód.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kétlépcsős Hitelesítés Ellenőrzése</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body background="../images/wallpaper.jpg">
<div class="login-container">
    <h2>Kétlépcsős Hitelesítés Ellenőrzése</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <p>Írja be a Google Authenticator alkalmazásban generált ellenőrző kódot.</p>
    <form action="verify_2fa.php" method="post">
        <div>
            <label for="2fa_code">Ellenőrző kód:</label>
            <input type="text" id="2fa_code" name="2fa_code" required>
        </div>
        <div>
            <button type="submit" name="verify">Ellenőrzés</button>
        </div>
    </form>
</div>
</body>
</html>

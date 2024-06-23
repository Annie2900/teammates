<?php
require_once '../vendor/autoload.php'; // Composer autoload

use RobThree\Auth\TwoFactorAuth;

// Függvény a véletlenszerű titkos kulcs generálásához
function generateSecretKey() {
    return (new TwoFactorAuth())->createSecret();
}

// Generálj egy titkos kulcsot
$shared_secret = generateSecretKey();

// Kiírjuk a generált titkos kulcsot
echo "Közös titkos kulcs: " . $shared_secret;
?>
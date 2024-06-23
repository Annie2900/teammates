<?php

const PARAMS = [
    "HOST" => 'localhost',
    "USER" => 'teammates',
    "PASS" => 'zuFoAHz3Hx82uC4',
    "DBNAME" => 'teammates',
    "CHARSET" => 'utf8mb4'
];

const SITE = 'https://teammates.stud.vts.su.ac.rs/proji/register/'; // enter your path on localhost

$dsn = "mysql:host=" . PARAMS['HOST'] . ";dbname=" . PARAMS['DBNAME'] . ";charset=" . PARAMS['CHARSET'];

$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $conn = new PDO($dsn, PARAMS['USER'], PARAMS['PASS'], $pdoOptions);
} catch (PDOException $e) {
    die("Sikertelen kapcsolódás: " . $e->getMessage());
}

// Define actions and messages here as before
$actions = ['login', 'register', 'forget'];

$messages = [
    0 => 'Nincs közvetlen hozzáférés!',
    1 => 'Ismeretlen felhasználó!',
    2 => 'Felhasználó ezzel a névvel már létezik, válasszon másikat!',
    3 => 'Ellenőrizze e-mailjét az aktiváláshoz!',
    4 => 'Töltse ki az összes mezőt!',
    5 => 'Kijelentkeztél!',
    6 => 'Fiókod aktiválva, most már bejelentkezhetsz!',
    7 => 'A jelszavak nem egyeznek!',
    8 => 'Az e-mail cím formátuma nem megfelelő!',
    9 => 'A jelszó túl rövid! Legalább 8 karakter hosszúnak kell lennie!',
    10 => 'A jelszó nem elég erős! (legalább 8 karakter, legalább 1 kisbetű, 1 nagybetű, 1 szám és 1 speciális karakter)',
    11 => 'Valami probléma volt az e-mail szerverrel. Később próbáljuk újra küldeni az e-mailt!',
    12 => 'A fiókod már aktiválva van!',
    13 => 'Ha van fiókod a weboldalunkon, e-mailt küldtünk neked a jelszó visszaállításához szükséges utasításokkal.',
    14 => 'Valami hiba történt a szerverrel.',
    15 => 'A token vagy más adat érvénytelen!',
    16 => 'Az új jelszavad beállítva, most már <a href="login.php" class="text-white">bejelentkezhetsz</a>!'
];


$emailMessages = [
    'register' => [
        'subject' => 'Regisztráció a weboldalon',
        'altBody' => 'Ez a szöveg plain text formátumban van, nem HTML e-mail kliensek számára'
    ],
    'forget' => [
        'subject' => 'Elfelejtett jelszó - új jelszó létrehozása',
        'altBody' => 'Ez a szöveg plain text formátumban van, nem HTML e-mail kliensek számára'
    ]
];

?>

<?php
require_once "dbcon.php";
require_once "functions_def.php";

// Debugging: Check if token is received
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
    echo "Token érkezettToken received: " . htmlspecialchars($token) . "<br>";
} else {
    echo "Nem érkezett token<br>";
    exit(); // Stop script execution
}

// Debugging: Check if token is of correct length
if (!empty($token) and strlen($token) === 40) {
    echo "Érvényes token<br>";

    // Check if the token exists in the database and is not expired
    $sql_check = "SELECT * FROM users WHERE BINARY registration_token = :token AND registration_expires > NOW()";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        $user = $stmt_check->fetch(PDO::FETCH_ASSOC);

        // Debugging: Display user info before updating
        echo "Felhasználó megtalálva: " . htmlspecialchars($user['email']) . "<br>";
        echo "Aktív állapot frissítés előtt: " . htmlspecialchars($user['active']) . "<br>";

        // Proceed with updating the user's status
        $sql_update = "UPDATE users SET active = 1, registration_token = '', registration_expires = NULL
                       WHERE BINARY registration_token = :token AND registration_expires > NOW()";
        try {
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt_update->execute();

            // Debugging: Check if the SQL statement is executed successfully
            if ($stmt_update->rowCount() > 0) {
                echo "Felhasználó sikeresen aktiválva!<br>";
                redirection('login.php?r=6');
            } else {
                echo "Semmi sem frissült.<br>";
                redirection('login.php?r=12');
            }
        } catch (PDOException $e) {
            echo 'Error lépett fel: ' . $e->getMessage();
        }
    } else {
        echo "Nem található ilyen felhasználó vagy lejárt a token<br>";
        redirection('login.php?r=12');
    }
} else {
    echo "Érvénytelen token formátum<br>";
    redirection('login.php?r=0');
}
?>

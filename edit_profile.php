<?php
session_start(); // session indítása
include 'dbcon.php'; // adatbázis kapcsolat
global $conn;

if(isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email']; // a bejelentkezett felhasználó email címe
} else {
    // Ha a felhasználó nincs bejelentkezve, valamilyen hibakezelés vagy átirányítás szükséges
    // Például:
    header("Location: login.php");
    exit(); // Az oldal végrehajtásának leállítása
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile szerkesztése</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Profil szerkesztése</h2>
    <form action="update_profile.php" method="post">
        <?php
        $sql_profile = "SELECT user_fname, user_lname, user_email, user_phone
                            FROM UsersInfo
                            WHERE user_email = '$user_email'";
        $result_profile = $conn->query($sql_profile);

        if ($result_profile->num_rows > 0) {
            $row_profile = $result_profile->fetch_assoc();
            ?>
            <div class="mb-3">
                <label for="fname" class="form-label">Keresztnév:</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $row_profile['user_fname']; ?>">
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Vezetéknév:</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $row_profile['user_lname']; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $row_profile['user_email']; ?>" disabled>
                <!-- Aktuális email cím megjelenítése, de letiltva a szerkesztést -->
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Telefonszám:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row_profile['user_phone']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Mentés</button>
        <?php } ?>
    </form>
</div>
</body>
</html>

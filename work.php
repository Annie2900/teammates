<?php
session_start();
include 'dbcon.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

// Felhasználó be van-e jelentkezve
if (!isset($_SESSION['del_id'])) {
    header("Location: index.php");
    exit();
}

$del_id = $_SESSION['del_id'];

// Ellenőrizze, hogy a bejelentkezett felhasználó futár-e
$sql = "SELECT * FROM DeliveryPerson WHERE del_id = :del_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['del_id' => $del_id]);
$deliveryPerson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$deliveryPerson) {
    header("Location: index.php");
    exit();
}

// Rendelés lefoglalása
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $sql = "UPDATE Orders SET del_id = :del_id, order_status = 1, order_date = NOW() WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['del_id' => $del_id, 'order_id' => $order_id]);
}

// Rendelés kézbesítése
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delivered_order_id'])) {
    $order_id = $_POST['delivered_order_id'];
    $sql = "UPDATE Orders SET order_status = 2, delivery_date = NOW() WHERE order_id = :order_id AND del_id = :del_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['order_id' => $order_id, 'del_id' => $del_id]);

    // Email küldése, ha a rendelés státusza 2-re (kézbesítve) változik
    sendOrderConfirmationEmail($order_id, $conn);
}

// Aktuális lefoglalt rendelés ellenőrzése
$sql = "SELECT * FROM Orders WHERE del_id = :del_id AND order_status = 1 LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute(['del_id' => $del_id]);
$current_order = $stmt->fetch(PDO::FETCH_ASSOC);

// Elérhető rendelések megjelenítése
$sql = "SELECT * FROM Orders WHERE order_status = 0";
$stmt = $conn->prepare($sql);
$stmt->execute();
$available_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mai nap dátumának lekérése YYYY-MM-DD formátumban
$currentDate = date('Y-m-d');

// Ma kezdődő és végződő időszak kiszámítása DATETIME összehasonlításhoz
$startTime = $currentDate . ' 00:00:00';
$endTime = $currentDate . ' 23:59:59';

// Ma kézbesített rendelések megjelenítése
$sql = "SELECT *, TIMESTAMPDIFF(SECOND, order_date, delivery_date) AS delivery_time 
        FROM Orders 
        WHERE del_id = :del_id 
          AND order_status = 2 
          AND delivery_date >= :startTime 
          AND delivery_date <= :endTime";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'del_id' => $del_id,
    'startTime' => $startTime,
    'endTime' => $endTime
]);
$delivered_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Értékelési token generálása és tárolása
function generateRatingToken($order_id, $user_id, $conn) {
    $token = bin2hex(random_bytes(32)); // Véletlen token generálása

    // Token lejárati idejének beállítása (pl.: 7 nap múlva)
    $tokenExpiration = date('Y-m-d H:i:s', strtotime('+7 days'));

    // Token tárolása az adatbázisban
    $sql = "INSERT INTO DeliveryRatingTokens (order_id, token, token_expiration) VALUES (:order_id, :token, :token_expiration)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['order_id' => $order_id, 'token' => $token, 'token_expiration' => $tokenExpiration]);

    return $token;
}

// Megjegyzés hozzáadása a kézbesített rendeléshez
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_order_id']) && isset($_POST['del_comment'])) {
    $order_id = $_POST['comment_order_id'];
    $del_comment = $_POST['del_comment'];

    $sql = "UPDATE Orders SET del_comment = :del_comment WHERE order_id = :order_id AND del_id = :del_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['del_comment' => $del_comment, 'order_id' => $order_id, 'del_id' => $del_id]);
}

// PHP code before the HTML section (omitting unchanged parts)

// Fetch comments for the current order's user
if ($current_order && isset($current_order['id_user'])) {
    $id_user = $current_order['id_user'];

    $sql = "SELECT del_comment FROM Orders WHERE id_user = :id_user AND del_comment IS NOT NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id_user' => $id_user]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munka Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
 	<link rel="stylesheet" href="css/work.css">
	<script src="js/work.js"></script>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Aktuális Rendelés</h2>
    <?php if ($current_order): ?>
        <div class="card mb-4">
            <div class="card-header">
                Rendelés #<?php echo htmlspecialchars($current_order['order_id']); ?>
            </div>
            <div class="card-body">
                <p>Város: <?php echo htmlspecialchars($current_order['order_city']); ?></p>
                <p>Utca: <?php echo htmlspecialchars($current_order['order_street']); ?></p>
                <p>Összesen: <?php echo htmlspecialchars($current_order['order_price']); ?> RSD</p>
                <!-- Display comments -->
			<div class="mb-3">
    			<label for="comments" class="form-label">Kézbesítő megjegyzések:</label>
    			<textarea class="form-control" id="comments" name="del_comment" rows="2" readonly><?php 
        			if (!empty($comments)) {
            			foreach ($comments as $comment) {
                			echo trim(htmlspecialchars($comment['del_comment'])) . "\n";
            			}
        			}
    			?></textarea>
			</div>
                <form action="work.php" method="POST">
                    <input type="hidden" name="delivered_order_id" value="<?php echo $current_order['order_id']; ?>">
                    <button type="submit" class="btn btn-success">Kézbesítve jelölés</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <br>
        <h2 class="mb-4">Elérhető Rendelések</h2>
        <div class="row">
            <?php if (!empty($available_orders)): ?>
                <?php foreach ($available_orders as $order): ?>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 col-xxl-2 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                Rendelés #<?php echo htmlspecialchars($order['order_id']); ?>
                            </div>
                            <div class="card-body">
                                <p>Város: <?php echo htmlspecialchars($order['order_city']); ?></p>
                                <p>Utca: <?php echo htmlspecialchars($order['order_street']); ?></p>
                                <p>Összesen: <?php echo htmlspecialchars($order['order_price']); ?> RSD</p>
                                <form action="work.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <button type="submit" class="btn btn-custom mt-3">Rendelés Elfogadása</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Jelenleg nincs elérhető rendelés.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h2 class="mb-4">Kézbesített Rendelések</h2>
    <div class="row">
        <?php if (!empty($delivered_orders)): ?>
            <?php foreach ($delivered_orders as $order): ?>
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 col-xxl-2 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            Rendelés #<?php echo htmlspecialchars($order['order_id']); ?>
                        </div>
                        <div class="card-body">
                            <p>Város: <?php echo htmlspecialchars($order['order_city']); ?></p>
                            <p>Utca: <?php echo htmlspecialchars($order['order_street']); ?></p>
                            <p>Összesen: <?php echo htmlspecialchars($order['order_price']); ?> RSD</p>
                            <p class="text-success">Kézbesítve</p>
                            <p>Kézbesítési Idő: <span class="delivery-time" data-time="<?php echo $order['delivery_time']; ?>"></span></p>
                            <form action="work.php" method="POST">
                                <div class="mb-3">
                                    <label for="comment_<?php echo $order['order_id']; ?>" class="form-label">Megjegyzés:</label>
                                    <textarea class="form-control comment-box" id="comment_<?php echo $order['order_id']; ?>" name="del_comment" rows="2"></textarea>
                                </div>
                                <input type="hidden" name="comment_order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" class="btn btn-custom">Megjegyzés Mentése</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Még nincsenek kézbesített rendelések.</p>
        <?php endif; ?>
    </div>
</div>

<?php

// Rendelés visszaigazoló e-mail küldése
function sendOrderConfirmationEmail($order_id, $conn) {
    session_start(); // Session kezdése

    try {
        // Felhasználó e-mail címének lekérése az Orders táblából
        $sql = "SELECT users.email, users.id_user FROM Orders JOIN users ON Orders.id_user = users.id_user WHERE Orders.order_id = :order_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['order_id' => $order_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $userEmail = $user['email'];

        // Egyedi token generálása és tárolása
        $token = generateRatingToken($order_id, $user['id_user'], $conn);

        // Email tartalma
        $mail = new PHPMailer(true);
		$mail->CharSet = "UTF-8";
        // SMTP beállítások
        $mail->isSMTP();
        $mail->Host = 'teammates.stud.vts.su.ac.rs'; // SMTP szerver címe
        $mail->SMTPAuth = true;
        $mail->Username = 'teammates'; // SMTP felhasználónév
        $mail->Password = 'zuFoAHz3Hx82uC4'; // SMTP jelszó
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
		$mail->isHTML(true);
    	$mail->Encoding = 'base64';
        $mail->setFrom('teammates@teammates.stud.vts.su.ac.rs', 'Teammates');
        $mail->addAddress($userEmail); // Címzett e-mail címe
    	
        $mail->Subject = 'Rendelés kézbesítve';
        $mail->Body = '<p>Kedves Felhasználó,</p>

		<p>Örömmel értesítjük, hogy rendelését sikeresen kézbesítettük. Reméljük, hogy minden a legnagyobb megelégedésére szolgált, és hogy elégedett volt a szolgáltatásainkkal.</p>
		<p>Kérjük, szánjon néhány percet arra, hogy értékelje tapasztalatait velünk. Véleménye és visszajelzései rendkívül fontosak számunkra, mert segítenek nekünk abban, hogy szolgáltatásainkat még jobban tudjuk fejleszteni.</p>
		<p>Köszönjük, hogy velünk volt, és várjuk visszajelzését!</p>
		<p>Üdvözlettel,<br>
		Teammates</p>
		<p>Kattintson az alábbi linkre az értékeléshez:</p> https://teammates.stud.vts.su.ac.rs/proji/register/rating.php?token=' . $token;
        // Email küldése
        $mail->send();
        return 'Email sikeresen elküldve.';
    } catch (Exception $e) {
        // Hibakezelés, ha az e-mail küldése nem sikerült
        return "Email küldése sikertelen: {$mail->ErrorInfo}";
    }
}
?>
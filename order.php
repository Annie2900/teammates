<?php
session_start();
include 'dbcon.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

// Check if the user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Check if the request method is POST and 'quantities' field is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    // Get the current user's ID from the session
    $userId = $_SESSION['id_user'];
    $quantities = $_POST['quantities'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $street = $_POST['street'];

    // Start a transaction
    $conn->beginTransaction();

    try {
        // Calculate total price of the order
        $totalPrice = 0;

        foreach ($quantities as $foodId => $quantity) {
            // Get the food price and discount from the database
            $sql = "SELECT food_price, food_discount FROM `Food` WHERE food_id = :food_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['food_id' => $foodId]);
            $food = $stmt->fetch(PDO::FETCH_ASSOC);
            $foodPrice = $food['food_price'];
            $foodDiscount = $food['food_discount'];

            // Calculate the actual price considering discount
            if ($foodDiscount !== null) {
                // If food_discount is not null, calculate price with discount
                $actualPrice = $foodDiscount * $quantity;
            } else {
                // If food_discount is null, use the original food_price
                $actualPrice = $foodPrice * $quantity;
            }

            // Increase the total order price by the current item's cost multiplied by quantity
            $totalPrice += $actualPrice;
        }

        // Insert the order into the Orders table
        $sql = "INSERT INTO `Orders` (id_user, order_date, order_city, order_street, order_status, order_price, phone) 
                VALUES (:id_user, NOW(), :order_city, :order_street, 0, :order_price, :phone)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id_user' => $userId,
            'order_city' => $city,
            'order_street' => $street,
            'order_price' => $totalPrice, // Use the calculated total price here
            'phone' => $phone
        ]);
        $orderId = $conn->lastInsertId();

        // Insert ordered food items into OrderedFood table
        foreach ($quantities as $foodId => $quantity) {
            // Insert the ordered food items into the OrderedFood table
            $sql = "INSERT INTO `OrderedFood` (order_id, food_id, order_amount) VALUES (:order_id, :food_id, :order_amount)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['order_id' => $orderId, 'food_id' => $foodId, 'order_amount' => $quantity]);
        }

        // Commit the transaction
        $conn->commit();

        // Send an order confirmation email
        sendOrderConfirmationEmail($userId, $orderId, $conn);

        // Store the success message in the session
        $_SESSION['message'] = "Rendelés sikeresen leadva! Az e-mail sikeresen elküldve.";
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        $conn->rollBack();
        // Store the error message in the session
        $_SESSION['message'] = "Hiba történt a rendelés feldolgozása során.";
    }

    // Redirect to food.php
    header("Location: food.php");
    exit();
} else {
    // Store the error message in the session
    $_SESSION['message'] = "Nincs megadva rendelési mennyiség.";
    header("Location: food.php");
    exit();
}



// Function to send order confirmation email
function sendOrderConfirmationEmail($userId, $orderId, $conn) {
    try {
        // Get the user's email from the database
        $sql = "SELECT email FROM `users` WHERE `id_user` = :id_user";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id_user' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $userEmail = $user['email'];

        // Initialize PHPMailer
        $mail = new PHPMailer(true);
		$mail->CharSet = "UTF-8";
        // Configure SMTP settings
        $mail->isSMTP();
        $mail->Host = 'teammates.stud.vts.su.ac.rs';  // SMTP server address
        $mail->SMTPAuth = true;
        $mail->Username = 'teammates'; // SMTP username
        $mail->Password = 'zuFoAHz3Hx82uC4'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
		$mail->isHTML(true);
    	$mail->Encoding = 'base64';
        // Set email data
        $mail->setFrom('teammates@teammates.stud.vts.su.ac.rs', 'Teammates');
        $mail->addAddress($userEmail); // Recipient's email address

        // Set email content
        $mail->isHTML(true);
        $mail->Subject = 'Megrendelés visszaigazolása';
        $mail->Body    = '<p>Tisztelt Felhasználó!</p>
    					<p>Köszönjük rendelését! Nagy örömmel értesítjük, hogy sikeresen rögzítettük a megrendelését. Hamarosan felvesszük Önnel a kapcsolatot a szállítási részletekkel kapcsolatban.</p>
    				<p>Amennyiben bármilyen kérdése merülne fel, ne habozzon felvenni velünk a kapcsolatot. Örömmel állunk rendelkezésére!</p>
    				<p>Köszönettel és Üdvözlettel,<br>
    				Teammates csapata</p>';

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        // Handle errors if the email sending fails
        $_SESSION['message'] .= " Az e-mail küldése során hiba történt: {$mail->ErrorInfo}";
    }
}
?>

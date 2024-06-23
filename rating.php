<?php
session_start();
include 'dbcon.php';
include 'functions_def.php';

function validateToken($token, $conn) {
    try {
        $currentDateTime = date('Y-m-d H:i:s');
        $sql = "SELECT order_id, token_expiration FROM DeliveryRatingTokens WHERE token = :token AND token_expiration > :currentDateTime";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Nem sikerült előkészíteni a SQL utasítást: " . implode(", ", $conn->errorInfo()));
        }
        $stmt->execute(['token' => $token, 'currentDateTime' => $currentDateTime]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            throw new Exception("Nincs találat a megadott tokenre.");
        }
        return $result;
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Token validálási hiba: " . $e->getMessage();
        header("Location: index.php");
        exit();
    }
}

function fetchDeliveryPersonId($orderId, $conn) {
    try {
        $sql = "SELECT del_id FROM Orders WHERE order_id = :order_id";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Nem sikerült előkészíteni a SQL utasítást: " . implode(", ", $conn->errorInfo()));
        }
        $stmt->execute(['order_id' => $orderId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            throw new Exception("Nincs találat a megadott rendeléshez tartozó futár azonosítójára.");
        }
        return $result['del_id'];
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Futár azonosító lekérdezési hiba: " . $e->getMessage();
        header("Location: index.php");
        exit();
    }
}

// Check if the token exists and is valid
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['error_message'] = "Hiányzik vagy üres a token paraméter.";
    header("Location: index.php");
    exit();
}

$token = $_GET['token'];
$tokenInfo = validateToken($token, $conn);

if (!$tokenInfo) {
    $_SESSION['error_message'] = "Érvénytelen vagy lejárt token!";
    header("Location: index.php");
    exit();
}

$order_id = $tokenInfo['order_id'];
$token_expiration = $tokenInfo['token_expiration'];

// Fetch delivery person id from Orders table
$delivery_person_id = fetchDeliveryPersonId($order_id, $conn);

if (!$delivery_person_id) {
    $_SESSION['error_message'] = "Hiba történt a futár azonosító lekérdezése során.";
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the food rating form is submitted
    if (isset($_POST['food_rating'], $_POST['food_comment'])) {
        $food_rating = $_POST['food_rating'];
        $food_comment = htmlspecialchars($_POST['food_comment']);

        $sql = "INSERT INTO Ratingcomment (food_rating, food_comment, order_id) VALUES (:food_rating, :food_comment, :order_id)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $_SESSION['error_message'] = "Hiba az SQL utasítás előkészítése során: " . implode(", ", $conn->errorInfo());
            header("Location: index.php");
            exit();
        }

        $stmt->bindParam(':food_rating', $food_rating, PDO::PARAM_INT);
        $stmt->bindParam(':food_comment', $food_comment, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Az étel értékelése sikeresen mentve.";
        } else {
            $_SESSION['error_message'] = "Hiba történt az étel értékelés mentése során: " . implode(", ", $stmt->errorInfo());
        }
    }

    // Check if the delivery person rating form is submitted
    if (isset($_POST['delivery_rating'], $_POST['delivery_comment'])) {
        $delivery_rating = $_POST['delivery_rating'];
        $delivery_comment = htmlspecialchars($_POST['delivery_comment']);

        $sql = "INSERT INTO DeliveryRatingComment (del_rating, del_comment, order_id, del_id) VALUES (:del_rating, :del_comment, :order_id, :del_id)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $_SESSION['error_message'] = "Hiba az SQL utasítás előkészítése során: " . implode(", ", $conn->errorInfo());
            header("Location: index.php");
            exit();
        }

        $stmt->bindParam(':del_rating', $delivery_rating, PDO::PARAM_INT);
        $stmt->bindParam(':del_comment', $delivery_comment, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':del_id', $delivery_person_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Az értékelések sikeresen mentve.";
        } else {
            $_SESSION['error_message'] = "Hiba történt a futár értékelés mentése során: " . implode(", ", $stmt->errorInfo());
        }
    }

    // Update token_expiration to one year ago from the current date
    $oneYearAgo = date('Y-m-d H:i:s', strtotime('-1 year'));
    $updateSql = "UPDATE DeliveryRatingTokens SET token_expiration = :expiration WHERE token = :token";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':expiration', $oneYearAgo, PDO::PARAM_STR);
    $updateStmt->bindParam(':token', $token, PDO::PARAM_STR);
    $updateStmt->execute();

    // Redirect user to index.php
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
   <title>Értékelje tapasztalatát</title> 
   <link rel="stylesheet" href="css/rating.css">
   <style> body {background-image: url('images/wallpaper.jpg');} </style>
</head>
<body>
    <h2>Értékeljen bátran</h2>
    
    <!-- Combined Form for Food and Delivery Person Ratings -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?token=" . urlencode($token); ?>" method="POST">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
        
        <!-- Food Rating Section -->
        <label for="food_rating">Étel értékelése (1-5):</label>
        <input type="number" id="food_rating" name="food_rating" min="1" max="5" required><br><br>
        <label for="food_comment">Étel észrevételei (legfeljebb 200 karakter):</label><br> 
        <textarea id="food_comment" name="food_comment" rows="4" cols="50" maxlength="200" required></textarea><br><br>
        
        <!-- Delivery Person Rating Section -->
        <label for="delivery_rating">Kiszállító értékelése (1-5):</label>
        <input type="number" id="delivery_rating" name="delivery_rating" min="1" max="5" required><br><br>
        <label for="delivery_comment">Kiszállító észrevételei (legfeljebb 200 karakter):</label><br> 
        <textarea id="delivery_comment" name="delivery_comment" rows="4" cols="50" maxlength="200" required></textarea><br><br>
        
        <!-- Submit Button for Both Forms -->
        <button type="submit">Mentés</button>
    </form>
</body>
</html>

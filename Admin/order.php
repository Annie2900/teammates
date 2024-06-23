<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    exit();
}

include '../dbcon.php';

// Fetch the admin ID using the email stored in the session
try {
    $stmt = $conn->prepare("SELECT admin_id FROM Admin WHERE admin_email = :admin_email");
    $stmt->bindParam(':admin_email', $_SESSION['admin_email']);
    $stmt->execute();
    $admin = $stmt->fetch();
    if ($admin) {
        $admin_id = $admin['admin_id'];
    } else {
        die("Admin not found.");
    }
} catch (PDOException $e) {
    die("Failed to fetch admin ID: " . $e->getMessage());
}

// Fetch delivery persons (workers) from the database
try {
    $stmt = $conn->prepare("SELECT del_id, del_email FROM DeliveryPerson");
    $stmt->execute();
    $deliveryPersons = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Failed to fetch delivery persons: " . $e->getMessage());
}

// Handle form submission to update orders with delivery person
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['assign_delivery'])) {
        $order_id = $_POST['order_id'];
        $del_id = $_POST['delivery_person'];

        try {
            $stmt = $conn->prepare("UPDATE Orders SET del_id = :del_id, admin_id = :admin_id WHERE order_id = :order_id");
            $stmt->bindParam(':del_id', $del_id);
            $stmt->bindParam(':admin_id', $admin_id);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Failed to update order: " . $e->getMessage());
        }
    }
}

// Fetch orders from the database
try {
    $stmt = $conn->prepare("SELECT * FROM Orders");
    $stmt->execute();
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Failed to fetch orders: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Menu</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/order.css">
</head>
<body background="../images/wallpaper.jpg">

<nav>
    <div class="navbar navbar-expand-lg pt-4">
        <div class="container-fluid">
            <a href="menu.php" class="brand text-decoration-none d-block d-lg-none fw-bold fs-1 ">Menü</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul id="nav-length" class="navbar-nav justify-content-between border-top border-2 text-center">
                    <li class="nav-item">
                        <a href="admin.php" class="nav-link border-hover py-3 ">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a href="user.php" class="nav-link border-hover py-3 ">Felhasználó</a>
                    </li>
                    <li class="nav-item">
                        <a href="worker.php" class="nav-link border-hover py-3 ">Munkás</a>
                    </li>
                    <li class="nav-item">
                        <a href="food.php" class="nav-link border-hover py-3 ">Étel</a>
                    </li>
                    <li class="nav-item">
                        <a href="order.php" class="nav-link border-hover py-3 ">Rendelések</a>
                    </li>
    				<li class="nav-item">
                            <a href="comments.php" class="nav-link border-hover py-3 ">Üzenet </a>
                    </li>
                    <li class="nav-item">
                        <form action="logout.php" method="post" style="text-align: right;">
                            <button type="submit" name="logout" class="logout-btn nav-link my-2 px-4 text-white">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Rendelések</h2>
    <table class="table table-bordered table-striped transparent-table">
        <thead>
            <tr>
                <th>Rendelés ID</th>
                <th>Felhasználó ID</th>
                <th>Rendelés Dátuma</th>
                <th>Város</th>
                <th>Utca</th>
                <th>Állapot</th>
                <th>Ár</th>
                <th>Megjegyzés</th>
                <th>Telefonszám</th>
                <th>Szállító ID</th>
                <th>Admin ID</th>
                <th>Szállítás Dátuma</th>
                <th>Szállító Személy</th>
                <th>Művelet</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['id_user']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_city']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_street']); ?></td>
                    <td>
    					<?php
    					$status = htmlspecialchars($order['order_status']);
    					if ($status == 0) {
        					echo 'Létrehozva, de nem felvett';
    					} elseif ($status == 1) {
        					echo 'Felvett';
    					} elseif ($status == 2) {
        					echo 'Kiszállítva';
    					} else {
        					echo 'Ismeretlen';
    					}
    					?>
					</td>
                    <td><?php echo htmlspecialchars($order['order_price']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_comment']); ?></td>
                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                    <td><?php echo htmlspecialchars($order['del_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['admin_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['delivery_date']); ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <select name="delivery_person" class="form-select">
                                <?php foreach ($deliveryPersons as $person): ?>
                                    <option value="<?php echo $person['del_id']; ?>"><?php echo $person['del_email']; ?></option>
                                <?php endforeach; ?>
                            </select>
                    </td>
                    <td>
                            <button type="submit" name="assign_delivery" class=" btn-custom">Hozzárendel</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

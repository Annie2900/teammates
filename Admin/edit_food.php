<?php
include '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food_id = $_POST['food_select'];
    $food_category = $_POST['food_category'];
    $food_name = $_POST['food_name'];
    $food_price = $_POST['food_price'];
    $food_quantity = $_POST['food_quantity'];
    $food_desc = $_POST['food_desc'];
    $alias = $_POST['alias'];

    $file_name = $_FILES['file']['name'];
    if ($file_name) {
        $file_temp = $_FILES['file']['tmp_name'];
        $ext_temp = explode(".", $file_name);
        $extension = end($ext_temp);
        $new_file_name = Date("YmdHis") . "-$alias.$extension";
        $directory = "../uploadedImages";
        $upload = "$directory/$new_file_name";

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        if (move_uploaded_file($file_temp, $upload)) {
            $stmt = $conn->prepare("UPDATE Food SET food_category = ?, food_name = ?, food_price = ?, food_quantity = ?, food_desc = ?, food_img = ? WHERE food_id = ?");
            $stmt->execute([$food_category, $food_name, $food_price, $food_quantity, $food_desc, $new_file_name, $food_id]);
        } else {
            echo "<p><b>Error uploading file!</b></p>";
        }
    } else {
        $stmt = $conn->prepare("UPDATE Food SET food_category = ?, food_name = ?, food_price = ?, food_quantity = ?, food_desc = ? WHERE food_id = ?");
        $stmt->execute([$food_category, $food_name, $food_price, $food_quantity, $food_desc, $food_id]);
    }

    header("Location: food.php");
}
?>
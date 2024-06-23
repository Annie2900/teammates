<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    exit();
}

include '../dbcon.php';  // Ensure this path is correct for your project structure

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_category = $_POST['food_category'];
    $food_name = $_POST['food_name'];
    $food_price = $_POST['food_price'];
    $food_quantity = $_POST['food_quantity'];
    $food_desc = $_POST['food_desc'];

    if ($_FILES['file']['error'] > 0) {
        echo "Something went wrong during file upload!";
        exit();
    } else {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $file_name = $_FILES['file']['name'];
            $file_temp = $_FILES['file']['tmp_name'];
            $ext_temp = explode(".", $file_name);
            $extension = end($ext_temp);

            if (isset($_POST['alias'])) {
                $alias = $_POST['alias'];
            } else {
                $alias = "";
            }

            $new_file_name = Date("YmdHis") . "-$alias.$extension";
            $directory = "../uploadedImages";
            $upload = "$directory/$new_file_name";

            if (!is_dir($directory)) {
                mkdir($directory);
            }

            if (!file_exists($upload)) {
                if (move_uploaded_file($file_temp, $upload)) {
                    try {
                        $sql = "INSERT INTO Food (food_category, food_name, food_price, food_quantity, food_img, food_desc) 
                                VALUES (:food_category, :food_name, :food_price, :food_quantity, :food_img, :food_desc)";
                        
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':food_category', $food_category);
                        $stmt->bindParam(':food_name', $food_name);
                        $stmt->bindParam(':food_price', $food_price);
                        $stmt->bindParam(':food_quantity', $food_quantity);
                        $stmt->bindParam(':food_img', $new_file_name);
                        $stmt->bindParam(':food_desc', $food_desc);

                        if ($stmt->execute()) {
                            echo "New food item added successfully!";
                        } else {
                            echo "Error: Could not execute the query.";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "Error during file upload.";
                }
            } else {
                echo "File with this name already exists!";
            }
        }
    }
}
?>

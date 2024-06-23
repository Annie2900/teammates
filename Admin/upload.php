<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload</title>
</head>

<body>
<?php


echo "<pre>";
var_dump($_FILES);
echo "</pre>";

if ($_FILES['file']["error"] > 0) {
    echo "Something went wrong during file upload!";
} else {
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {

        $file_name = $_FILES['file']["name"];
        $file_temp = $_FILES["file"]["tmp_name"];
        $file_size = $_FILES["file"]["size"];
        $file_type = $_FILES["file"]["type"];
        $file_error = $_FILES['file']["error"];
        $full_path = $_FILES['file']["full_path"]; 
        echo exif_imagetype($file_temp) . "<br>";

        if (!exif_imagetype($file_temp))
            exit("File is not a picture!");

        $ext_temp = explode(".", $file_name); //
        $extension = end($ext_temp);

        if (isset($_POST['alias'])) {
            $alias = $_POST['alias'];
        } else {
            $alias = "";
        }

        $new_file_name = Date("YmdHis") . "-$alias.$extension";
        // 20171110084338.jpg
        // 20191112134305-vts.jpg
        $directory = "../uploadedImages";

        $upload = "$directory/$new_file_name"; // images/20191112134305-vts.jpg

        /*

        201711289282.extension

        */

        if (!is_dir($directory)) //is_dir("images")
            mkdir($directory);

        if (!file_exists($upload)) //images/back.png
        {
            if (move_uploaded_file($file_temp, $upload)) {

                $size = getimagesize($upload);
                var_dump($size);
                foreach ($size as $key => $value)
                    echo "$key = $value<br>";

                echo "<img src=\"$upload\" $size[3] alt=\"$file_name\">";

            } else
                echo "<p><b>Error!</b></p>";
        } else
            echo "<p><b>File with this name already exists!</b></p>";
    }

    foreach (get_defined_constants() as $key => $value)
        echo "$key = $value<br>";

}
?>
</body>
</html>
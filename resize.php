<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileType = $_FILES['image']['type'];
    if ($fileType == "image/jpeg" || $fileType == "image/png" || $fileType == "image/jpg") {
        $uploadDir = "uploads/";
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $newWidth = $_POST['width'];
            $newHeight = $_POST['height'];
            list($width, $height) = getimagesize($uploadFile);
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            if ($fileType == "image/jpeg" || $fileType == "image/jpg") {
                $source = imagecreatefromjpeg($uploadFile);
            } elseif ($fileType == "image/png") {
                $source = imagecreatefrompng($uploadFile);
            }
            imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            $resizedFile = $uploadDir . "resized_" . basename($_FILES['image']['name']);
            if ($fileType == "image/jpeg" || $fileType == "image/jpg") {
                imagejpeg($newImage, $resizedFile);
            } elseif ($fileType == "image/png") {
                imagepng($newImage, $resizedFile);
            }
            echo "<h2>Original Image:</h2>";
            echo "<img src='$uploadFile' alt='Original Image' width='300'>";
            echo "<h2>Resized Image:</h2>";
            echo "<img src='$resizedFile' alt='Resized Image' width='300'>";
            unlink($uploadFile);
            imagedestroy($source);
            imagedestroy($newImage);
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "Only JPG, JPEG, and PNG files are allowed.";
    }
}
?>

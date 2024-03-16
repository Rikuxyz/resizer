<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileType = $_FILES['image']['type'];
    $fileName = $_FILES['image']['name'];
    $fileTmpName = $_FILES['image']['tmp_name'];
    $fileSize = $_FILES['image']['size'];
    
    $uploadDir = "uploads/";
    $uploadFile = $uploadDir . basename($fileName);

    // Memeriksa apakah file yang diupload adalah gambar
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (in_array($fileType, $allowedTypes)) {
        // Memeriksa ukuran file
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($fileSize <= $maxFileSize) {
            // Memindahkan file yang diupload ke direktori uploads
            if (move_uploaded_file($fileTmpName, $uploadFile)) {
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
                $resizedFile = $uploadDir . "resized_" . $fileName;
                if ($fileType == "image/jpeg" || $fileType == "image/jpg") {
                    imagejpeg($newImage, $resizedFile);
                } elseif ($fileType == "image/png") {
                    imagepng($newImage, $resizedFile);
                }
                echo "<h2>Original Image:</h2>";
                echo "<img src='$uploadFile' alt='Original Image' width='300'>";
                echo "<h2>Resized Image:</h2>";
                echo "<img src='$resizedFile' alt='Resized Image' width='300'>";
                // Menampilkan tombol unduh
                echo "<a href='$resizedFile' download><button>Download Resized Image</button></a>";
            } else {
                echo "Failed to upload file.";
            }
        } else {
            echo "File is too large. Maximum file size is 5MB.";
        }
    } else {
        echo "Only JPG, JPEG, and PNG files are allowed.";
    }
}
?>

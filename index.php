<?php
include 'FileImageUploader.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
}

?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="image">
    <input type="text" name="filename">
    <input type="submit" value="Upload">
</form>

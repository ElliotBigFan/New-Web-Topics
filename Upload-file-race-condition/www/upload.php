<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['file'])) {
        echo "No file";
        exit;
    }
    $uploaddir = __DIR__ . '/uploads/';
    // TÊN DỄ DỰ ĐOÁN: 0123456789_<file_name>
    $name = "0123456789" . "_" . basename($_FILES['file']['name']);
    $target = $uploaddir . $name;
    // LƯU thẳng vào webroot trước khi validate (vulnerable)
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        echo "Saved as $name<br>";
        // Gọi validator (giả lập AV với delay)
        include __DIR__ . '/validator.php';
        validate_and_maybe_delete($target);
        echo "<br><a href='/uploads/$name'>Open uploaded file (may be deleted)</a>";
    } else {
        echo "Upload failed";
    }
    exit;
}
?>
<form method="POST" enctype="multipart/form-data">
    Upload file: <input type="file" name="file">
    <input type="submit" value="Upload">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $filename = basename($_FILES['file']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if ($ext !== 'gif') {
            echo "❌ Only .gif files are allowed!";
            exit;
        }

        $target = "uploads/" . $filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            echo "✅ Uploaded successfully: <a href='$target'>$target</a><br>";
        } else {
            echo "❌ Upload failed!";
        }
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" />
    <button type="submit">Upload GIF</button>
</form>

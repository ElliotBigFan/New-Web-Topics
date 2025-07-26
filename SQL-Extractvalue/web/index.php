<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("db", "root", "root", "sqli_lab");

if ($conn->connect_error) {
    die("Connection failed");
}

$name = $_GET['name'] ?? '';

$sql = "INSERT INTO candidates (name, village, status)
        VALUES ('$name', 'Konoha', 'Tham gia')";

try {
    $conn->query($sql);
    echo "✅ Gửi thành công!";
} catch (Exception $e) {
    echo "<pre>❌ Lỗi SQL: " . $e->getMessage() . "</pre>";
}
?>

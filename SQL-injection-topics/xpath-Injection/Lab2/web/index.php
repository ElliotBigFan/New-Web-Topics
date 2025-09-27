<?php
require_once 'validate.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("db", "root", "root", "sqli_lab");

if ($conn->connect_error) {
    die("Connection failed");
}

$result = null;

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $name = validate($name); // WAF tại đây

    $sql = "INSERT INTO candidates (name, village, status)
            VALUES ('$name', 'Konoha', 'Tham gia')";

    $query = $conn->query($sql);

    if ($query) {
        $result = "Đăng ký thành công";
    } else {
        $result = "Đăng ký thất bại";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>WAF Insert Lab</title></head>
<body>
    <h2>Đăng ký làm ninja làng Lá</h2>
    <form method="GET">
        <input type="text" name="name" placeholder="Tên của bạn">
        <input type="submit" value="Gửi">
    </form>
    <p><?= $result ?? '' ?></p>
</body>
</html>

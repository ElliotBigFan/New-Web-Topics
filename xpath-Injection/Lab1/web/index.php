<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("db", "root", "root", "sqli_lab");

if ($conn->connect_error) {
    die("Connection failed");
}

$search = $_GET['q'] ?? '';

$sql = "SELECT * FROM notes WHERE content LIKE '%$search%'";

$result = $conn->query($sql);

if ($result) {
    echo "<h3>Search results:</h3>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>{$row['content']}</p>";
    }
}
?>

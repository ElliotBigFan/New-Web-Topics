<?php
// LFI lab
if (isset($_GET['page'])) {
    $page = $_GET['page'] . ".php";
    include($page);
} else {
    echo "<h2>Welcome to LFI Lab</h2>";
    echo "Try ?page=upload to upload file.<br>";
}
?>

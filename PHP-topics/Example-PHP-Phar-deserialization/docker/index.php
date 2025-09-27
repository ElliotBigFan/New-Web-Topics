<?php
class LogFile {
    public $filename;
    function __destruct() {
        // trigger phar metadata
        md5_file($this->filename);
    }
}
class Evil {
    function __destruct() {
        file_put_contents('info.php', '<?php phpinfo() ?>');
    }
}

if(isset($_GET['file'])){
    $obj = new LogFile();
    $obj->filename = $_GET['file']; // vuln here
    echo "Done\n";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phar-Deserialization</title>
</head>
<body>
    <h1>Phar Deserialization Exploit Demo</h1>
    <p>This page demonstrates a PHP deserialization vulnerability using Phar files.</p>
    <p>Check the directory for the created <code>info.php</code> file after triggering the exploit.</p>

</body>
</html>

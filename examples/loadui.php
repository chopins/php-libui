<?php

use UI\UI;

include dirname(__DIR__) . '/src/UI.php';
$uidl = getenv('LIBUI_PATH');
if($uidl) {
    $so = $uidl;
} else {
    $so = '/opt/libui/lib64/libui.so';
}
if(!file_exists($so)) {
    throw new \Exception("libui dynamic library not found in $so, set 'LIBUI_PATH' environment variables assign libui.so path");
}
$ui = new UI($so);

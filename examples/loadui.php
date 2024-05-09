<?php

use UI\UI;

include dirname(__DIR__) . '/src/UI.php';
if (defined('LIBUI_PATH')) {
    $so = constant('LIBUI_PATH');
} else if ($uidl) {
    $uidl = getenv('LIBUI_PATH');
    $so = $uidl;
} else {
    $so = '/opt/libui/lib64/libui.so';
}
if (!file_exists($so)) {
    throw new \Exception("libui dynamic library not found in $so, set 'LIBUI_PATH' environment variables or defined LIBUI_PATH constant assign libui.so path");
}
$ui = new UI($so);

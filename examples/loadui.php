<?php

use UI\UI;

spl_autoload_register(function ($class) {
    $classInfo = explode('\\', $class);
    array_shift($classInfo);
    array_unshift($classInfo, dirname(__DIR__) . '/src');
    $path = join(DIRECTORY_SEPARATOR, $classInfo) . '.php';
    if (file_exists($path)) {
        include_once $path;
    }
});
if (defined('LIBUI_PATH')) {
    $so = constant('LIBUI_PATH');
} else {
    $so = getenv('LIBUI_PATH');
}
if(!$so) {
    $so = '/opt/libui/lib64/libui.so';
}

if (!file_exists($so)) {
    throw new \Exception("libui dynamic library not found in $so, set 'LIBUI_PATH' environment variables or defined LIBUI_PATH constant assign libui.so path");
}
$ui = new UI($so);

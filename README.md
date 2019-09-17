# php-libui
PHP bindings to the [libui](https://github.com/andlabs/libui) C library.

libui is a lightweight, portable GUI library that uses the native GUI technologies of each platform it supports.

### Requirements
* PHP >= 7.4
* PHP FFI extension available
* libui latest version

### A Simple Example
```php
include '/src/UI.php';
$ui = new UI('/usr/lib64/libui.so');
$ui->init();
$mainwin = $ui->newWindow("libui Control Gallery", 640, 480,1);
$ui->controlShow($mainwin);
$ui->main();
```

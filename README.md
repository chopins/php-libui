# php-libui
PHP bindings to the [libui](https://github.com/andlabs/libui) C library.

### Requirements
* PHP >= 7.4
* FFI extension available

### A Simple Example
```php
include '/src/UI.php';
$ui = new UI('/usr/lib64/libui.so');
$ui->init();
$mainwin = $ui->newWindow("libui Control Gallery", 640, 480, 1);
$ui->controlShow($mainwin);
$ui->main();
```
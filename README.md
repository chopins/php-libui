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
$ui = new \UI\UI('/usr/lib64/libui.so');
$ui->init();
$mainwin = $ui->newWindow("libui Control Gallery", 640, 480,1);
$ui->controlShow($mainwin);
$ui->main();
```

# Use UIBuild create UI

##  Basic Usage:
```php
include '/src/UI.php';
$ui = new \UI\UI('/usr/lib64/libui.so');
$config = ['title' => 'test', 'width' => 600,'height' => 450];
$build = $ui->build($config);
$build->show();
```

## bild config structure

build config is array, main key contain `body`,`menu` and *window attribute key*, similar the following:
```php
[
    'title' => 'window title name',
    'width' => 600,
    'menu' => [],
    'body'  => []
]
```

## window attribute key list:
| key    | type          | Description     | Default Vlaue |
| ------ | ------------- | --------------- | ------------- |
| title  | string        | window title    | No Win Title  |
| width  | int           | window width    | 800           |
| height | int           | window height   | 640           |
| border | int           | window border   | 0             |
| margin | int           | window margin   | 0             |
| quit   | EventCallable | quit callback   | null          |
| close  | EventCallable | close callback  | null          |
| resize | EventCallable | resize callback | null          |

above table type `EventCallable` is php `array`, element 0 is `callable`, element 1 is pass to callable data of user. similar `['function_name', 'pass_data_string']`,  __Note: The doc of EventCallable type is same as this__

## menu array
The array level 1 of item element is one menu, similar:
```php
[
    [
        'title' => 'File',
        'id'    => 'menu_id_1',
        'childs' => [
            ['title' => 'New File'],
            ['title' => 'Open File'],
        ]
    ],
    [
        'title' => 'Edit',
        'id'    => 'menu_id_1',
        'childs' => [
            ['title' => 'Undo'],
            ['title' => 'Copy'],
        ]
    ],
]
```
Top menu only contain `title`, `id`, `childs` , the `title` value will display in window, every element of `childs` array is submenu that display in drop-down menu. if element is string and equral `hr` will display a separator  
__current Build UI Config of submenu only contain the following attr:__
| key   | type          | Description                                                 | require |
| ----- | ------------- | ----------------------------------------------------------- | ------- |
| title | string        | menu title                                                  | yes     |
| type  | string        | menu type, value is `text` or `checkbox`, default is `text` | no      |
| click | EventCallable | click callback                                              | no      |

## body array  
every element key of `body` array is node control name, __Build UI__ current only support the following control:

1. `button`, Button control,contail the following attr:
   1. `type`,  potential value is following:
      1. `file`, open file button
      2. `save`, save file button
      3. `font`, select font button
      4. `color`, select color button
      5. `button`, is default value
   2. `title`, button label name
   3. `chick`, it is `EventCallable`, when click callback, when  `file` and `save` is after select file call
   4. `change`,only when `color` and `font` available, select color or font be call
2. `box` box layout,the following attr:
   1. `dir` layout direction, Specify one of `h` is horizontal and default value, `v` is vertical
   2. `padded`, padding value, `int` type, default is `0`
   3. `child_fit` Whether to automatically adapt
   4. `childs` sub control list
3. `group` group layout, have `title` and `margin`, `childs` attr
4. `label`  text control, only has `title` attr
5. `hr`   horizontal separator, no attr
6. `vr`   vertical separator, no attr
7. `input`  input control, the following attr:
   1. `type`, specify one of the following value:
      1. `password`  password entry control
      2. `search`   search entry control
      3. `textarea` multiline entry control
      4. `radio`    radio 
      5. `select`   select
      6. `checkbox` checkbox
      7. `text`, is default value
   2. `readonly`
   3. `wrap`, only `textarea` is available, `bool` type, `false` is non wrapping textarea
   4. `option`, `radio` and `select` available,`array` type, element value is option title, key is natural order number
   5. `change`, is `EventCallable`, exclude `checkbox` and `radio`
   6. `title`, `checkbox` available
   7. `click`, only `radio` and `checkbox` available
8. `form`   form layout, has `padded`, `childs` attr
9. `grid`   grid layout, the following attr:
    1. `padded`
    2. `child_left`
    3. `child_top`
    4. `child_width`
    5. `child_height`
    6. `child_hexpand`
    7. `child_haligin`
    8. `child_vexpand`
    9. `child_valign`
    10. `childs`
10. `table`  table control, has following sub key :
    1. `th`, is `array`, every element of value is array, key is id, has the following attr:
       1. `editable`, `bool` type, the column is whether editable
       2. `textColor`widthwidth
       3. `title`
       4. `type`, specify value of `button`, `image`, `imgtext`, `progress`, `checkbox`, `checkboxtext`, `color`, `text`
    2. `tbody` is `array`, the table row value list, every element is one row value, when row of column is array has the following attr:
       1. `image` type, has `src` `width` `height`
       2. `color` type has `r`,`g`,`b`
    3. `rowBgcolor`
    4. `change` is `array`, every element is one row change callback list, column is `callable`
11. `tab`    tab control, has `page` sub array, `page` array every element value is page child control and key is page title
12. `img`   image control, has flowing attr:
    1. `src` is image paths list, `array` type, every element value is image file path, key is natural order number
    2. `width`  the image control width, default is `src` first element image width
    3. `height` the image control heigth, default is `src` first element image width

## Control common method:
* show()
* hide()
* enable()
* disbale()
* destroy()
* parent()
* setParent($parent)
* isVisible()
* isEnabled()

specify control see class statement in control directory

## UI method
see `UI.php`

## UIBuild method
see `UIBuild.php`
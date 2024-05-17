# [中文说明](README_zh.md)
# php-libui
PHP bindings to the [libui](https://github.com/andlabs/libui) C library.

libui is a lightweight, portable GUI library that uses the native GUI technologies of each platform it supports.

### Requirements
* PHP >= 7.4
* PHP FFI extension available
* libui latest version

### A Simple Example
see https://github.com/chopins/http-request  

first download libui dynamic library from https://github.com/andlabs/libui/releases or checkout source for self-build, then load in php code use following code
```php
include '/src/UI.php';
$ui = new \UI\UI('/usr/lib64/libui.so'); //load libui dynamic library
$ui->init();
$mainwin = $ui->newWindow("libui Control Gallery", 640, 480,1);
$ui->controlShow($mainwin);
$ui->main();
```

## Note: When Call libui C function need remove `ui` prefix of name, then to lower case first char

# Use UIBuild create UI

##  Basic Usage:
```php
include '/src/UI.php';
$ui = new \UI\UI('/usr/lib64/libui.so');
$config = ['title' => 'test', 'width' => 600,'height' => 450];
$build = $ui->build($config);
$build->show();
```

## build config structure
* build config is xml file, see `tests/uibuild.xml`
* **In xml file, all event attribute name must be prefix `on`**, `onclick`,`onchange`,`ondraw`, value is callable name, it is not Event instance, callable name support php variable and php callable:`$var`,`func_name`,`CLASS::method`, do not support array callable. variable is GLOBALS
* attr value support GLOBALS variable and constant, prefix `$``@` as `$var` or `@CONST`
* also build config support array, main key contain `body`,`menu` and *window attribute key*; in config array, element key is attr name, element value is attr value,similar the following:
```php
[
    'title' => 'window title name',
    'width' => 600,
    'menu' => [],
    'body'  => [
        'name' => 'box'
        'childs' => []
    ]
]
```

## window attribute key list:
| attr   | type          | Description     | Default Vlaue |
| ------ | ------------- | --------------- | ------------- |
| title  | string        | window title    | No Win Title  |
| width  | int           | window width    | 800           |
| height | int           | window height   | 640           |
| border | int           | window border   | 0             |
| margin | int           | window margin   | 0             |
| fullscreen | int           | window margin   | 0             |
| quit   | \UI\Event | quit callback   | null          |
| close  | \UI\Event | close callback  | null          |
| resize | \UI\Event | resize callback | null          |

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

| attr   | type          | Description                                                 | require |
| ------ | ------------- | ----------------------------------------------------------- | ------- |
| title  | string        | menu title                                                  | yes     |
| type   | string        | menu type, value is `text`,`quit`,`about`,`preferences` or `checkbox`, default is `text` | no      |
| click  | \UI\Event | click callback                                              | no      |
| childs | array         | child menu list                                             | no      |

## body array
every element key of `body` array is control config, `widget` element is control name and `attr` element is control attr. see `examples/table.php` __Build UI__ current only support the following control:

1. `button`, Button control,contain the following attr:

   | attr   | type          | Description                                                                                                                                                                                                                                                            | require |
   | ------ | ------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------- |
   | type   | string        | control type, smiliar HTML `<button>` tag of `type` attr, potential value is following:<br />  1. `file`, open file button<br />2. `save`, save file button<br />3. `font`, select font button<br />4. `color`, select color button<br />5. `button`, is default value | yes     |
   | title  | string        | button label name                                                                                                                                                                                                                                                      | yes     |
   | click  | \UI\Event | when click callback, when type `file` and `save` is after select file call                                                                                                                                                                                             | no      |
   | change | \UI\Event | only when `color` and `font` available, select color or font be call                                                                                                                                                                                                   | no      |
   | id     | string        |                                                                                                                                                                                                                                                                        | no      |

2. `box` box layout,the following attr:

   | attr      | type   | Description                                                                           | require |
   | --------- | ------ | ------------------------------------------------------------------------------------- | ------- |
   | dir       | string | layout direction, Specify one of `h` is horizontal and default value, `v` is vertical | yes     |
   | padded    | int    | padding value,  default is `0`                                                        | no      |
   | child_fit | int    | Whether to automatically adapt                                                        | no      |
   | childs    | array  | sub control list                                                                      | no      |
   | id        | string |                                                                                       | no      |

3. `group` group layout, have `title` and `margin`, `child`,`id` attr
4. `label`  text control, only has `title` and `id` attr
5. `hr`   horizontal separator, no attr
6. `vr`   vertical separator, no attr
7. `input`  input control, the following attr:

   | attr     | type          | Description                                                                                                                                                                                                                                                                                                                                                            | require |
   | -------- | ------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------- |
   | type     | string        | miliar HTML `<input>` tag of `type` attr, specify one of the following value:<br />1. `password`  password entry control<br />2. `search`   search entry control<br />3. `textarea` multiline entry control<br />4. `radio`    radio<br />5. `select`   select<br />6. `checkbox` checkbox<br />7. `text`, is default value<br />8.`number` is Spinbox<br />9.`slider` | yes     |
   | readonly | bool          | whether readonly                                                                                                                                                                                                                                                                                                                                                       | no      |
   | wrap     | bool          | only `textarea` is available, `false` is non wrapping textarea                                                                                                                                                                                                                                                                                                         | no      |
   | option   | array         | `radio` and `select` available, element value is option title, key is natural order number                                                                                                                                                                                                                                                                             | no      |
   | change   | \UI\Event | exclude `checkbox` and `radio`                                                                                                                                                                                                                                                                                                                                         | no      |
   | title    | string        | `checkbox` available                                                                                                                                                                                                                                                                                                                                                   | yes     |
   | click    | \UI\Event | only `radio` and `checkbox` available                                                                                                                                                                                                                                                                                                                                  | no      |
   | min      | int           | `number` and `slider` available                                                                                                                                                                                                                                                                                                                                        | yes     |
   | max      | int           | `number` and `slider` available                                                                                                                                                                                                                                                                                                                                        | yes     |
   | id       | string        |                                                                                                                                                                                                                                                                                                                                                                        | no      |
   | editable | bool          | `select` available                                                                                                                                                                                                                                                                                                                                                     | no      |
   
8. `form`   form layout, has `padded`, `childs`,`id` attr, child widget has label attr
9. `grid`   grid layout, the following attr:

   | attr          | type   | Description | require |
   | ------------- | ------ | ----------- | ------- |
   | padded        | int    |             | no      |
   | child_left    | int    |             | no      |
   | child_top     | int    |             | no      |
   | child_width   | int    |             | no      |
   | child_height  | int    |             | no      |
   | child_hexpand | int    |             | no      |
   | child_halign | int    |             | no      |
   | child_vexpand | int    |             | no      |
   | child_valign  | int    |             | no      |
   | childs        | array  |             | no      |
   | id            | string |             | no      |
 
10. `table`  table control, table cell has change event, it is following sub key :

    | attr  | type   | Description                                                                                                                                                                                                                                                                                                               | require |
    | ----- | ------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------- |
    | th    | array  | every element of value is array, key is id, has the following attr:<br />1. `editable`, `bool` type, the column is whether editable<br />2. `textColor`<br />3. `title`<br />4. `type`, specify value of `button`, `image`, `imgtext`, `progress`, `checkbox`, `checkboxtext`, `color`, `text` <br />5. widget has `change` attr, for row column change callback                            | yes     |
    | tbody | array  | the table row value list, every element is one row value, when row of column is array has the following attr:<br />1. `image` type, has `src` `width` `height`<br />2. `color` type has `r`,`g`,`b`<br />3. `rowBgcolor` | yes     |
    | id    | string |                                                                                                                                                                                                                                                                                                                           | no      |

11. `tab`    tab control, has `page` sub array, `page` array every element value is page child control and key is page title
12. `img`   image control, has flowing attr:

    | attr   | type   | Description                                                                              | require |
    | ------ | ------ | ---------------------------------------------------------------------------------------- | ------- |
    | src    | array  | is image paths list, every element value is image file path, key is natural order number | yes     |
    | width  | int    | the image control width, default is `src` first element image width                      | no      |
    | height | int    | the image control heigth, default is `src` first element image width                     | no      |
    | id     | string |                                                                                          | no      |

13. `datetime` datetime control
    
    | attr   | type          | description                                   | require |
    | ------ | ------------- | --------------------------------------------- | ------- |
    | type   | string        | specify one of value `time`,`date`,`datetime` | yes     |
    | change | \UI\Event |                                               | no      |
    | id     | string        |                                               | no      |

14.  `progress`, has `id` attr
15.  unsupport control must call libui C function by `UI\UI`
16.  `UI\Event`, all event callback class, The signature of the callback is as follows:
```php
/**
 * @param UI\Event $callable   The object instance of current event 
 * 
 * */
function (UI\Event $callable) {}
```

## Control common method:
* show()
* hide()
* enable()
* disable()
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

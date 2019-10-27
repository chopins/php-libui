# php-libui
PHP 绑定 [libui](https://github.com/andlabs/libui) 的 C 库.

libui 一个轻量级的且可移植的GUI库，其使用了原生的GUI技术为每一个平台提高支持

### 必须
* PHP >= 7.4
* PHP FFI 扩展可用
* libui 最新版本

### A Simple Example
首先从  https://github.com/andlabs/libui/releases 下载 libui 动态库，或者检出代码自行编译，然后使用后面例子的方法加载
```php
include '/src/UI.php';
$ui = new \UI\UI('/usr/lib64/libui.so'); //加载 libui 动态库
$ui->init();
$mainwin = $ui->newWindow("libui Control Gallery", 640, 480,1);
$ui->controlShow($mainwin);
$ui->main();
```
## 注意：访问libui C 函数时，函数名需去掉`ui`前缀，然后首字母小写

# 使用 UIBuild 创建UI

##  基本用法:
```php
include '/src/UI.php';
$ui = new \UI\UI('/usr/lib64/libui.so');
$config = ['title' => 'test', 'width' => 600,'height' => 450];//UI 配置数组
$build = $ui->build($config);
$build->show();
```

## 构建配置数组结构

构建配置数组第一层包含`body` `menu` 以及 window 属性；在配置数组中键为属性，值为属性值。 类似后面代码所展示的结构。
```php
[
    'title' => 'window title name',
    'width' => 600,
    'menu' => [],
    'body'  => [
        ['name' => 'box'],
        ['name' => 'grid']
    ]
]
```

## window 属性列表:
| 属性名 | 类型          | 描述            | 默认值       |
| ------ | ------------- | --------------- | ------------ |
| title  | string        | window title    | No Win Title |
| width  | int           | window width    | 800          |
| height | int           | window height   | 640          |
| border | int           | window border   | 0            |
| margin | int           | window margin   | 0            |
| quit   | \UI\Event | quit callback   | null         |
| close  | \UI\Event | close callback  | null         |
| resize | \UI\Event | resize callback | null         |

## 菜单数组 menu
这个数组中的每一个元素为一个菜单配置，结构类似：
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
顶级菜单只有 `title`, `id`, `childs` 几个子键(属性), `title`值为菜单名,`childs`数组是子菜单即下拉菜单。如果`childs`的元素是字符串且等于`hr`将显示分割线
__当前UI配置中的菜单只支持下面的属性:__

| 属性   | 类型          | 描述                                                        | 必须 |
| ------ | ------------- | ----------------------------------------------------------- | ---- |
| title  | string        | menu title                                                  | yes  |
| type   | string        | menu type, value is `text` or `checkbox`, default is `text` | no   |
| click  | \UI\Event | click callback                                              | no   |
| childs | array         | child menu list                                             | no   |

## body 数组
`body`数组的每一个元素为一个UI控件的配置，元素中`name`子元素为控件名，`attr`子元数为配置，当前 __Build UI__ 配置只支持入如下控件名，

1. `button`, Button control,包含下面的属性:
   
   | 属性名 | 类型          | 描述                                                                                                                                                                                                                                     | 必须 |
   | ------ | ------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---- |
   | type   | string        | 控件类型，类似HTML的`<button>`标签的`type`属性，可能值如下:<br />2. `file`, open file button<br />1. `save`, save file button<br />2. `font`, select font button<br />3. `color`, select color button<br />3. `button`, is default value | yes  |
   | title  | string        | button label name                                                                                                                                                                                                                        | yes  |
   | chick  | \UI\Event | when click callback, 当`type`为 `file` 或 `save` 时，选择文件后会被调用，并会将文件名传给回调函数                                                                                                                                        | no   |
   | change | \UI\Event | only when `color` and `font` available, select color or font be call                                                                                                                                                                     | no   |
2. `box` 盒布局 ,the following attr:
   | 属性名    | 类型   | 描述                                                                                  | 必须 |
   | --------- | ------ | ------------------------------------------------------------------------------------- | ---- |
   | dir       | string | layout direction, Specify one of `h` is horizontal and default value, `v` is vertical | yes  |
   | padded    | int    | padding value, default is `0`                                                         | no   |
   | child_fit | bool   | Whether to automatically adapt                                                        | no   |
   | childs    | array  | sub control list                                                                      | no   |
3. `group` 组布局, have `title` and `margin`, `childs` attr
4. `label` 文本标签控件, only has `title` attr
5. `hr`  水平分割线, no attr
6. `vr`  垂直分割线, no attr
7. `input`  输入类控件, the following attr:
   
   | 属性名   | 类型          | 描述                                                                                                                                                                                                                                                                                                                         | 必须 |
   | -------- | ------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---- |
   | type     | string        | 类似HTML的`<input>`标签的`type`属性，specify one of the following value:<br />1. `password`  password entry control<br />2. `search`   search entry control<br />3. `textarea` multiline entry control<br />4. `radio`    radio<br />5. `select`   select<br />6. `checkbox` checkbox<br />7. `text`, is default value<br /> | yes  |
   | readonly | bool          |                                                                                                                                                                                                                                                                                                                              | no   |
   | wrap     | bool          | only `textarea` is available, `false` is non wrapping textarea                                                                                                                                                                                                                                                               | no   |
   | option   | array         | `radio` and `select` available,element value is option title, key is natural order number                                                                                                                                                                                                                                    | no   |
   | change   | \UI\Event | exclude `checkbox` and `radio`                                                                                                                                                                                                                                                                                               | no   |
   | title    | string        | checkbox` available                                                                                                                                                                                                                                                                                                          | no   |
   | click    | \UI\Event | only `radio` and `checkbox` available                                                                                                                                                                                                                                                                                        | no   |
8. `form` 表单布局, has `padded`, `childs` attr
9.  `grid` 网格布局, the following attr:
    
   | 属性名        | 类型  | 描述 | 必须 |
   | ------------- | ----- | ---- | ---- |
   | padded        | int   |      | no   |
   | child_left    | int   |      | no   |
   | child_top     | int   |      | no   |
   | child_width   | int   |      | no   |
   | child_height  | int   |      | no   |
   | child_hexpand | int   |      | no   |
   | child_haligin | int   |      | no   |
   | child_vexpand | int   |      | no   |
   | child_valign  | int   |      | no   |
   | childs        | array |      | no   |

10. `table`  表格控件, has following sub key :
    
   | 属性名 | 类型  | 描述                                                                                                                                                                                                                                                                                                                      | 必须 |
   | ------ | ----- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---- |
   | th     | array | every element of value is array, key is id, has the following attr:<br />1. `editable`, `bool` type, the column is whether editable<br />2. `textColor`<br />3. `title`<br />4. `type`, specify value of `button`, `image`, `imgtext`, `progress`, `checkbox`, `checkboxtext`, `color`, `text`                            | yes  |
   | tbody  | array | the table row value list, every element is one row value, when row of column is array has the following attr:<br />1. `image` type, has `src` `width` `height`<br />2. `color` type has `r`,`g`,`b`<br />3. `rowBgcolor`<br />4. `change` is `array`, every element is one row change callback list, column is `callable` | yes  |

11. `tab` 可切换页控件, has `page` sub array, `page` array every element value is page child control and key is page title
12. `img` 图片控件， has flowing attr:
    
   | 属性   | 类型  | 描述                                                                                     | 必须 |
   | ------ | ----- | ---------------------------------------------------------------------------------------- | ---- |
   | src    | array | is image paths list, every element value is image file path, key is natural order number | yes  |
   | width  | int   | the image control width, default is `src` first element image width                      | no   |
   | height | int   | the image control heigth, default is `src` first element image width                     | no   |

13. 构建配置未支持控件使用`UI\UI`直接访问`libui` C 函数

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
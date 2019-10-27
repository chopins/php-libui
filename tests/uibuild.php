<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */
use UI\UIBuild;
use UI\Event;
use UI\Control\Area;
use UI\Struct\AreaDrawParams;

include_once dirname(__DIR__) . '/examples/loadui.php';

$build;

function removeSamePrefix($a, $b)
{
    $alen = strlen($a);
    $blen = strlen($b);
    $looplen = $alen > $blen ? $blen : $alen;
    for ($i = 0; $i < $looplen; $i++) {
        if ($a[$i] != $b[$i]) {
            break;
        }
    }
    return substr($b, $i);
}

function msg($s)
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

    $file = removeSamePrefix(__DIR__.'/', $trace[0]['file']);
    $line = $trace[0]['line'];

    echo date('Y-m-d H:i:s ') . "[$file($line)] $s " . PHP_EOL;
}

$quit = new Event(function() {
    global $build;
    msg('app quit');
    $build->destroyWin();
    return 1;
});
$close = new Event(function() use($ui) {
    msg('window close');
    $ui->quit();
    return 1;
});

$openfile = new Event(function($s, $w, $d, $f) use($ui) {
    msg("open file $f");
});
$openfile->onBefore(function() {
    global $build;
    return $build->openFile();
});

$buttonOpenFile = new Event(function($b, $data, $f) {
    msg("open file $f");
});
$buttonSaveFile = new Event(function($b, $data, $f) {
    msg("save to file $f");
});

$drawText = new Event(function($hander, Area $area, AreaDrawParams $param) use ($ui) {
    msg('draw text');
    try {
        
        $build = $area->getBuild();
        $txt = "Drawing strings with libui is done with the uiAttributedString and uiDrawTextLayout objects.\nuiAttributedString lets you have a variety of attributes: ";
        
        $string = new \UI\Control\AttributeString($build, ['string' => $txt]);

        $string->appendUnattributed(',');
 
        $textPrams = $area->newTextLayoutParams();
        $textPrams->string = $string;
        $font = $build->getControlById('font-btn-test')->getValue();

        $textPrams->defaultFont = $font;
        
        $textPrams->width = $param->areaWidth;
        $textPrams->align = $ui::DRAW_TEXT_ALIGN_LEFT;

        $layout = new UI\Control\DrawText($build, ['params' => $textPrams]);
      
        $area->drawText($layout, 0, 0);
        $layout->free();
    } catch (\Exception $e) {
        echo $e;
    } catch (\Error $e) {
        echo $e;
    }
});

$canvasDraw = new Event(function($hander, Area $area, AreaDrawParams $param) use($ui) {
    try {
        $path = $area->drawPath(['fillMode' => $ui::DRAW_FILL_MODE_WINDING]);
        $path->newFigure($param->clipX + 5, $param->clipY + 5);
        $path->lineTo($param->clipX + $param->clipWidth - 5, $param->clipY + $param->clipHeight - 5);
        $path->end();
    } catch (\Error $e) {
        echo $e;
    } catch (\Exception $e) {
        echo $e;
    }
});

$config = [
    'title' => 'hello',
    'width' => 900,
    'height' => 640,
    'margin' => 0,
    'border' => 0,
    'quit' => $quit,
    'close' => $close,
    'menu' => [
        ['title' => 'File',
            'childs' => [
                ['title' => 'Open File',
                    'click' => $openfile
                ],
                ['title' => 'New File',],
                'hr',
                ['type' => 'quit']
            ]
        ],
        ['title' => 'Edit',
            'childs' => [
                ['type' => 'checkbox', 'title' => 'Fullscreen'],
                ['type' => 'preferences']
            ]
        ],
        ['title' => 'Help',
            'childs' => [
                ['type' => 'about']
            ]
        ]
    ],
    'body' => [
        'name' => 'tab',
        'page' => [
            'From Page' => [
                [
                    'name' => 'form',
                    'padded' => '1',
                    'stretchy' => 1,
                    'childs' => [
                        'Form1-Label1' => [
                            'stretchy' => 0,
                            [
                                'name' => 'button',
                                'title' => 'Button1',
                                'stretchy' => 0
                            ],
                            [
                                'name' => 'input',
                                'title' => 'Button2',
                            ],
                            ['name' => 'hr'],
                            [
                                'name' => 'label',
                                'title' => 'Label Control',
                            ]
                        ],
                        'Progress' => [
                            [
                                'name' => 'progress',
                                'id' => 'progress_id',
                                'stretchy' => 0
                            ],
                        ],
                        'Datetime' => [
                            [
                                'name' => 'datetime',
                                'type' => 'date',
                            ],
                            [
                                'name' => 'datetime',
                                'type' => 'time',
                            ],
                            [
                                'name' => 'datetime',
                                'type' => 'datetime',
                            ]
                        ],
                    ]
                ],
            ],
            'Grid Page' => [
                [
                    'name' => 'grid',
                    'padded' => 1,
                    'child_left' => 1,
                    'child_top' => 1,
                    'child_width' => 20,
                    'child_height' => 10,
                    'child_hexpand' => 10,
                    'child_haligin' => 1,
                    'child_vexpand' => 20,
                    'child_valign' => 1,
                    'childs' => [
                        ['name' => 'button', 'type' => 'file', 'title' => 'Open File', 'child_left' => 1, 'click' => $buttonOpenFile],
                        ['name' => 'button', 'type' => 'save', 'title' => 'Save File', 'child_left' => 10, 'click' => $buttonSaveFile],
                        ['name' => 'vr'],
                        ['name' => 'button', 'type' => 'font', 'id' => 'font-btn-test', 'title' => 'Select Font', 'child_left' => 20],
                        ['name' => 'button', 'type' => 'color', 'title' => 'Select Color',
                            'child_top' => 2, 'child_left' => 10],
                        ['name' => 'input', 'type' => 'text', 'child_top' => 3],
                    ]
                ]
            ],
            'Group Page 1' => [
                [
                    'name' => 'group',
                    'title' => 'Group 1 Title',
                    'margin' => 1,
                    'child' => [
                        'name' => 'box',
                        'dir' => 'h',
                        'child_fit' => 1,
                        'childs' => [
                            [
                                'name' => 'form',
                                'padded' => 1,
                                'childs' => [
                                    'label' => [['name' => 'label', 'title' => 'FormLabel1'], ['name' => 'label', 'title' => 'FormLabel2']],
                                    'textarea' => [['name' => 'input', 'type' => 'textarea', 'wrap' => 0]],
                                    'password' => [['name' => 'input', 'type' => 'password']],
                                    'number' => [['name' => 'input', 'type' => 'number', 'min' => 0, 'max' => 100]],
                                    'slider' => [['name' => 'input', 'type' => 'slider', 'min' => 0, 'max' => 60]],
                                    'search' => [['name' => 'input', 'type' => 'search']],
                                    'radio' => [['name' => 'input', 'type' => 'radio', 'option' => ['Radio 1', 'Radio 2', 'Radio 3']]],
                                    'select' => [['name' => 'input', 'type' => 'select', 'option' => ['Select item 1', 'Select item 2', 'Select item 3', 'Select item 4',]]],
                                    'checkbox' => [['name' => 'input', 'type' => 'checkbox', 'title' => 'CheckBoxItme1'],
                                        ['name' => 'input', 'type' => 'checkbox', 'title' => 'CheckBoxItme2'],
                                        ['name' => 'input', 'type' => 'checkbox', 'title' => 'CheckBoxItme3']],
                                ]]
                        ]
                    ]
                ]
            ],
            'Group Page 2' => [
                [
                    'name' => 'group',
                    'title' => 'Group 2 Title A',
                    'margin' => 1,
                    'child' => [
                        'name' => 'canvas', 'draw' => $drawText
                    ]
                ],
                [
                    'name' => 'group',
                    'title' => 'Group 2 Title B',
                    'margin' => 1,
                    'child' => [
                        'name' => 'canvas'
                    ]
                ]
            ]
        ]
    ]
];

$build = new UIBuild($ui, $config);
$progressControl = $build->getControlById('progress_id');
$progressControl->setValue(50);
$build->show();

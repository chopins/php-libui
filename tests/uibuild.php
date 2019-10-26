<?php

use UI\UIBuild;
use UI\Event;

include_once dirname(__DIR__) . '/examples/loadui.php';

$build;
$quit = new Event(function() {
    global $build;
    print('app quit');
    $build->destroyWin();
    return 1;
});
$close = new Event(function() use($ui) {
    print('window close');
    $ui->quit();
    return 1;
});

$openfile = new Event(function($s, $w, $d, $f) use($ui) {
    print("open file $f");
});
$openfile->onBefore(function() {
    global $build;
    return $build->openFile();
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
        ['name' => 'tab',
            'attr' => [
                'page' => [
                    'From Page' => [
                        [
                            'name' => 'form',
                            'attr' => [
                                'padded' => '1',
                                'stretchy' => 1,
                                'childs' => [
                                    'Form1-Label1' => [
                                        'stretchy' => 0,
                                        [
                                            'name' => 'button',
                                            'attr' => [
                                                'title' => 'Button1'
                                            ],
                                            'stretchy' => 1
                                        ],
                                        [
                                            'name' => 'input',
                                            'attr' => [
                                                'title' => 'Button2'
                                            ],
                                        ],
                                        [
                                            'name' => 'label',
                                            'attr' => [
                                                'title' => 'Label Control'
                                            ],
                                        ]
                                    ]
                                ]
                            ]
                        ],
                    ],
                    'Grid Page' => [
                        [
                            'name' => 'grid',
                            'attr' => [
                                'padded' => 1,
                                'childs' => [
                                    
                                ]
                            ]
                        ]
                    ],
                    'Group Page' => [
                        [
                            'name' => 'group',
                            'attr' => [
                                'title' => 'Group Title',
                                'margin' => 1
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$build = new UIBuild($ui, $config);
$build->show();

<?php

include __DIR__ . '/loadui.php';

main();

function main()
{
    global $ui;
    $config = [
        'title' => 'test',
        'width' => 600,
        'height' => 450,
        'menu' => [
            [
                'title' => 'File',
                'childs' => [
                    ['title' => 'New File'],
                    ['title' => 'Open File'],
                ]
            ],
            [
                'title' => 'Edit',
                'childs' => [
                    ['title' => 'Find']
                ]
            ]
        ],
        'body' => [
            [
                'name' => 'box',
                'dir' => 'v',
                'child_fit' => 1,
                'childs' => [
                    'table' => [
                        'th' => [
                            ['title' => 'Colum1', 'idx' => 0, 'type' => 'text'],
                            ['title' => 'Colum2', 'idx' => 1, 'type' => 'button'],
                            ['title' => 'Colum3', 'idx' => 2, 'type' => 'text'],
                        ],
                        'tbody' => [
                            [1, 'button0', 3],
                            [1, 'button1', 3],
                            [1, 'button2', 3]
                        ],
                    ]
                ]
            ],
        ]
    ];


    return $ui->build($config)->show();
}

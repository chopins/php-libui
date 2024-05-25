<?php
include __DIR__ . '/loadui.php';
main();

function main()
{
    global $ui;
    $config = [
        'title' => 'test',
        'width' => 900,
        'height' => 900,
        'close' => $ui->event(function ($e){
            $e->ui()->quit();
            return 1;
        }),
        'menu' => [
            [
                'title' => 'File',
                'childs' => [
                    ['title' => 'New File'],
                    ['title' => 'Open File'],
                    ['title' => 'Quit', 'click' => $ui->event(function ($e){
                        $e->ui()->quit();
                        return 1;
                    })],
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
                'widget' => 'box',
                'dir' => 'v',
                'child_fit' => 1,
                'childs' => [
                    [
                        'widget' => 'table',
                        'th' => [
                            ['widget' => 'input', 'title' => 'Colum1', 'idx' => 0, 'type' => 'text'],
                            ['widget' => 'input', 'title' => 'Colum2', 'idx' => 1, 'type' => 'button'],
                            ['widget' => 'input', 'title' => 'Colum3', 'idx' => 2, 'type' => 'text'],
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

<?php

use UI\UIBuild;

include_once dirname(__DIR__) . '/examples/loadui.php';


$config = [
    'title' => 'hello',
    'width' => 320,
    'height' => 240,
    'margin' =>  1,
    'close' => ['onClosing', null],
    'body' => [

    ]
];

$build = new UIBuild($ui, $config);
main($ui, $build);
function onClosing($w, $data): int
{
    global $ui;
    $ui->quit();
    return 1;
}
$build->show();

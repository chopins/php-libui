<?php

use UI\Control;
use UI\UIBuild;

include_once dirname(__DIR__) . '/examples/loadui.php';


$config = [
    'title' => 'hello',
    'width' => 320,
    'height' => 240,
    'margin' =>  1,
    'close' => ['onClosing', null],
    'body' => []
];

$build = new UIBuild($ui, $config);
$subControl = testControl($ui, $build);
$win = $build->getWin();

if (!is_array($subControl)) {
    $subControl = [$subControl];
}

foreach ($subControl as $sub) {
    if (is_subclass_of($sub, '\UI\Control')) {
        $win->addChild($sub);
    } elseif($sub instanceof FFI\CData) {
        $win->windowSetChild($sub);
    }
}
function onClosing($w, $data): int
{
    global $ui;
    $ui->quit();
    return 1;
}
$build->show();

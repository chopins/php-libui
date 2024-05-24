<?php

use UI\UIBuild;

include_once dirname(__DIR__) . '/examples/loadui.php';


$config = [
    'title' => 'hello',
    'width' => 680,
    'height' => 480,
    'margin' =>  1,
    'close' => $ui->event(function ($e): int {
        global $ui;
        $ui->quit();
        return 1;
    }),
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
    } elseif ($sub instanceof FFI\CData) {
        $win->windowSetChild($sub);
    }
}

$build->show();

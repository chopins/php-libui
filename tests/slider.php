<?php

include_once __DIR__.'/win.php';

function main(\UI\UI $ui, \UI\UIBuild $build) {
    $box = new \UI\Control\Box($build, ['dir' => 'v']);
    $win = $build->getWin();
    $sl = $ui->newSlider(1, 100);
    $win->addChild($box);
    $ui->boxAppend($box->getUIInstance(), $sl, 1);
}
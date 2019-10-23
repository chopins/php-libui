<?php

include_once __DIR__.'/win.php';

function testControl(\UI\UI $ui, \UI\UIBuild $build) {
    $box = new \UI\Control\Box($build, ['dir' => 'v']);
    $sl = $ui->newSlider(1, 100);
    $ui->boxAppend($box->getUIInstance(), $sl, 1);
    return $box;
}
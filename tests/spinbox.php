<?php

include_once __DIR__.'/win.php';

function main($ui, $build) {
    $win = $build->getWin();
    $sp = $ui->newSpinbox(1, 100);
    $win->windowSetChild($sp);
    $ui->spinboxSetValue($sp, 80);
    $ui->spinboxOnChanged($sp,  function() use($ui, $sp) {
        $int = $ui->spinboxValue($sp);
        var_dump($int);
    }, null);
}
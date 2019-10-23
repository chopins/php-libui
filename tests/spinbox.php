<?php

include_once __DIR__.'/win.php';

function testControl($ui, $build) {
    $sp = $ui->newSpinbox(1, 100);
    $ui->spinboxSetValue($sp, 80);
    $ui->spinboxOnChanged($sp,  function() use($ui, $sp) {
        $int = $ui->spinboxValue($sp);
        var_dump($int);
    }, null);
    return $sp;
}
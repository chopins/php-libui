<?php

use UI\Control\Area;
use UI\Control\Box;

include_once __DIR__ . '/win.php';

function testControl(\UI\UI $ui, \UI\UIBuild $build)
{
   $box = new Box($build, ['dir' => 'v']);

   $area = new Area($build, [
      'type' => 'scroll',
      'width' => 100, 'height' => 200,
      'draw' => $ui->event(function ($e) {
         var_dump('draw');
      }),
      'mouseEvent' => $ui->event(function ($e) {
      }),
      'mouseCrossed' => $ui->event(function ($e) {
      }),
      'keyEvent' => $ui->event(function ($e) {
      }),
      'dragBroken' => $ui->event(function ($e) {
      }),
   ]);

   $box->appendChild($area);

   return $box;
}

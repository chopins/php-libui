<?php

use UI\Control\Area;
use UI\Control\Box;
use UI\Control\Button;

include_once __DIR__ . '/win.php';

function testControl(\UI\UI $ui, \UI\UIBuild $build)
{
   $box = new Box($build, ['dir' => 'v']);

   $area = new Area($build, [
      'type' => 'scroll',
      'width' => 100, 'height' => 200,
      'draw' => $ui->event(function ($e) {
         var_dump(date('H:i:s ') . 'draw');
      }),
      'mouseEvent' => $ui->event(function ($e) {
         var_dump(date('H:i:s ') . 'mouseEvent '. $e->mouseEvent['down'].'|' . $e->mouseEvent['up']);
      }),
      'mouseCrossed' => $ui->event(function ($e) {
         var_dump(date('H:i:s ') . 'mouseCrossed  ' . $e->left);
      }),
      'keyEvent' => $ui->event(function ($e) {
         var_dump(date('H:i:s ') . 'keyEvent ' . $e->keyEvent['key'] . '|' . $e->keyEvent['key']);
      }),
      'dragBroken' => $ui->event(function ($e) {
         var_dump(date('H:i:s ') . 'dragBroken');
      }),
   ]);

   $box->appendChild($area);

   return $box;
}

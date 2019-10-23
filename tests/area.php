<?php

use UI\Control\Box;

include_once __DIR__ . '/win.php';

function testControl(\UI\UI $ui, \UI\UIBuild $build)
{
   $box = new Box($build, ['dir' => 'v']);

   $handler = $ui->new('struct handler { uiAreaHandler ah;}', false);
   $handler->ah->Draw = fn()=>null;
   $handler->ah->MouseEvent = fn()=>null;
   $handler->ah->MouseCrossed = fn()=>null;
   $handler->ah->DragBroken = fn()=>null;
   $handler->ah->KeyEvent = fn()=>1;

   $handlerPtr = $ui->addr($handler);

   register_shutdown_function(function() use($ui, $handler) {
      $ui->ffi()::free($handler);
   });
   $ptr = $ui->cast('uiAreaHandler *', $handlerPtr);

   $area = $ui->newArea($ptr);
   $box->boxAppend($area, 1);

   return $box;
}

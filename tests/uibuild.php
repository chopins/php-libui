<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

use UI\UIBuild;
use UI\Control\DrawText;
use UI\Struct\DrawTextAlign;
use UI\Struct\TextLayoutParams;

include_once dirname(__DIR__) . '/examples/loadui.php';

$build;

function removeSamePrefix($a, $b)
{
    $alen = strlen($a);
    $blen = strlen($b);
    $looplen = $alen > $blen ? $blen : $alen;
    for ($i = 0; $i < $looplen; $i++) {
        if ($a[$i] != $b[$i]) {
            break;
        }
    }
    return substr($b, $i);
}

function msg($s)
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

    $file = removeSamePrefix(__DIR__ . '/', $trace[0]['file']);
    $line = $trace[0]['line'];

    echo date('Y-m-d H:i:s ') . "[$file($line)] $s " . PHP_EOL;
}

function quit ($e) {
    global $build;
    msg('app quit');
    $build->destroyWin();
    return 1;
}
$close = function ($e) {
    msg('window close');
    $e->ui()->quit();
    return 1;
};
$tableChange = function($e) {
var_dump($e->value);
};
$tableCheck = function($e) {
var_dump($e->value);
};

 function openfile($e){
    $f = $e->file;
    msg("open file $f");
}

function buttonOpenFile ($e) {
    msg("open file " .  $e->file);
}
function buttonSaveFile($e) {
    msg("save to file " . $e->file);
}

function drawText ($e){
    msg('draw text');
    try {

        $build = $e->build();
        $txt = "Drawing strings with libui is done with the uiAttributedString and uiDrawTextLayout objects.\nuiAttributedString lets you have a variety of attributes: ";
        $font = $build->getControlById('font-btn-test')->getValue();
        $color = $build->getControlById('color-btn-test')->getValue();
        $string = $build->createItem(['widget' => 'string', 'string' => $txt, 'color' => $color]);
        $textPrams = new TextLayoutParams($build, $string, $font, $e->params->areaWidth, DrawTextAlign::DRAW_TEXT_ALIGN_CENTER);
        $layout = DrawText::newFromParams($build, $textPrams);

        $e->getTarget()->drawText($layout, 0, 0);
        $layout->free();
    } catch (\Exception $e) {
        echo $e;
    } catch (\Error $e) {
        echo $e;
    }
}

function canvasDraw ($e){
    try {
        $path = $e->getTarget()->drawPath(['fillMode' => $e->getUI()::DRAW_FILL_MODE_WINDING]);
        $path->newFigure($e->param->clipX + 5, $e->param->clipY + 5);
        $path->lineTo($e->param->clipX + $e->param->clipWidth - 5, $e->param->clipY + $e->param->clipHeight - 5);
        $path->end();
    } catch (\Error $e) {
        echo $e;
    } catch (\Exception $e) {
        echo $e;
    }
}


$build = new UIBuild($ui, __DIR__ .'/uibuild.xml');
$progressControl = $build->getControlById('progress_id');
$progressControl->setValue(50);
$build->show();

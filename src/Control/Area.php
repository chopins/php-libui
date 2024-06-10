<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

namespace UI\Control;

use FFI;
use UI\Control;
use FFI\CData;
use UI\Struct\Matrix;
use UI\Control\Path;
use UI\Struct\AreaDrawParams;
use UI\Struct\StrokeParams;
use UI\Struct\Brush;
use UI\Struct\WindowResizeEdge;

/**
 * @property-read string $type
 * @property-read int $width
 * @property-read int $height
 * @property-read \UI\Event $draw
 * @property-read \UI\Event $mouse\UI\Event
 * @property-read \UI\Event $mouseCrossed
 * @property-read \UI\Event $dragBroken
 * @property-read \UI\Event $keyEvent
 * 
 */
class Area extends Control
{
    const CTL_NAME = 'canvas';

    private $handler = null;
    protected $context = null;

    public static $mousePriKey = 1;
    public static $mouseMidKey = 2;
    public static $mouseRightKey = 3;
    public static $dragPriKey = 1;
    public static $dragMidKey = 2;
    public static $dragPriMidKey = 3;
    public static $dragRightKey = 4;
    public static $dragPriRightKey = 5;
    public static $dragRightMidKey = 6;
    public static $dragThreeKey = 7;

    protected function newControl(): CData
    {
        $handler = $this->areaHandler();
        $this->attr['type'] = $this->attr['type'] ?? '';
        if ($this->attr['type'] == 'scroll') {
            $this->instance = self::$ui->newScrollingArea($handler, $this->attr['width'], $this->attr['height']);
        } else {
            $this->instance = self::$ui->newArea($handler);
        }
        return $this->instance;
    }

    public function getHnadler()
    {
        return $this->handler;
    }

    protected function areaHandler()
    {
        $this->handler = self::$ui->new('uiAreaHandler', false);
        $this->handler->Draw = [$this, 'draw'];
        $this->handler->MouseEvent = [$this, 'mouseEvent'];
        $this->handler->MouseCrossed = [$this, 'mouseCrossed'];
        $this->handler->DragBroken = [$this, 'dragBroken'];
        $this->handler->KeyEvent = [$this, 'keyEvent'];

        return FFI::addr($this->handler);
    }

    public function draw($handler, $area, $params)
    {
        try {
            if (empty($this->attr['draw'])) {
                return;
            }

            $handlerArr = [
                'draw' => $handler[0]->Draw,
                'mouseEvent' => $handler[0]->MouseEvent,
                'mouseCrossed' => $handler[0]->MouseCrossed,
                'dragBroken' => $handler[0]->DragBroken,
                'keyEvent' => $handler[0]->KeyEvent
            ];

            $this->context = $params[0]->Context;
            $areaParam = AreaDrawParams::instance($this->build, $params[0]);
            $this->attr['draw']->trigger('draw', $this, ['handler' => $handlerArr, 'params' => $areaParam]);
        } catch (\Error $e) {
            echo $e;
        }
    }

    public function mouseEvent($handler, $area, $mouseEvent)
    {
        try {
            $handlerArr = [
                'draw' => $handler[0]->Draw,
                'mouseEvent' => $handler[0]->MouseEvent,
                'mouseCrossed' => $handler[0]->MouseCrossed,
                'dragBroken' => $handler[0]->DragBroken,
                'keyEvent' => $handler[0]->KeyEvent
            ];
            $mouseEventArr = [
                'x' => $mouseEvent[0]->X,
                'y' => $mouseEvent[0]->Y,
                'areaWidth' => $mouseEvent[0]->AreaWidth,
                'areaHeight' => $mouseEvent[0]->AreaHeight,
                'down' => $mouseEvent[0]->Down,
                'up' => $mouseEvent[0]->Up,
                'count' => $mouseEvent[0]->Count,
                'modifiers' => $mouseEvent[0]->Modifiers,
                'drag' => $mouseEvent[0]->Held1To64,
            ];
            if ($mouseEventArr['down'] == self::$mousePriKey && $mouseEventArr['count'] == 1 && isset($this->attr['click'])) {
                $this->attr['click']->trigger('click', $this, ['handler' => $handlerArr, 'mouseEvent' => $mouseEventArr]);
            } else if ($mouseEventArr['down'] == self::$mousePriKey && $mouseEventArr['count'] == 2 && isset($this->attr['dblclick'])) {
                $this->attr['dblclick']->trigger('click', $this, ['handler' => $handlerArr, 'mouseEvent' => $mouseEventArr]);
            } else if ($mouseEventArr['drag'] == self::$dragPriKey && isset($this->attr['drag'])) {
                $this->attr['drag']->trigger('drag', $this, ['handler' => $handlerArr, 'mouseEvent' => $mouseEventArr]);
            } else if (isset($this->attr['mouseevent'])) {
                $this->attr['mouseevent']->trigger('mouseEvent', $this, ['handler' => $handlerArr, 'mouseEvent' => $mouseEventArr]);
            }
        } catch (\Error $e) {
            echo $e;
        }
    }

    /**
     * be called if mouse enter or out Area
     * @param int $out  if out Area is 1, else is 0
     */
    public function mouseCrossed($handler, $area, $out)
    {
        try {
            if (empty($this->attr['mousecrossed'])) {
                return;
            }

            $handlerArr = [
                'draw' => $handler[0]->Draw,
                'mouseEvent' => $handler[0]->MouseEvent,
                'mouseCrossed' => $handler[0]->MouseCrossed,
                'dragBroken' => $handler[0]->DragBroken,
                'keyEvent' => $handler[0]->KeyEvent
            ];
            $this->attr['mousecrossed']->trigger('mouseCrossed', $this, ['handler' => $handlerArr, 'out' => $out]);
        } catch (\Error $e) {
            echo $e;
        }
    }

    /**
     * Be called if a mouse drag is interrupted by the system
     */
    public function dragBroken($handler, $area)
    {
        try {
            if (empty($this->attr['dragbroken'])) {
                return;
            }

            $handlerArr = [
                'draw' => $handler[0]->Draw,
                'mouseEvent' => $handler[0]->MouseEvent,
                'mouseCrossed' => $handler[0]->MouseCrossed,
                'dragBroken' => $handler[0]->DragBroken,
                'keyEvent' => $handler[0]->KeyEvent
            ];
            $this->attr['dragbroken']->trigger('dragBroken', $this, ['handler' => $handlerArr]);
        } catch (\Error $e) {
            echo $e;
        }
    }

    public function keyEvent($handler, $area, $keyEvent)
    {
        try {
            if (empty($this->attr['keyevent'])) {
                return;
            }

            $handlerArr = [
                'draw' => $handler[0]->Draw,
                'mouseEvent' => $handler[0]->MouseEvent,
                'mouseCrossed' => $handler[0]->MouseCrossed,
                'dragBroken' => $handler[0]->DragBroken,
                'keyEvent' => $handler[0]->KeyEvent
            ];

            $keyEventAttr = [
                'key' => $keyEvent[0]->Key,
                'extKey' => $keyEvent[0]->ExtKey,
                'modifier' => $keyEvent[0]->Modifier,
                'modifiers' => $keyEvent[0]->Modifiers,
                'up' => $keyEvent[0]->Up,
            ];
            $this->attr['keyevent']->trigger('keyEvent', $this, ['handler' => $handlerArr, 'keyEvent' => $keyEventAttr]);
        } catch (\Error $e) {
            echo $e;
        }
    }

    public function setSize(int $w, int $h)
    {
        $this->areaSetSize($w, $h);
    }

    public function queueRedrawAll()
    {
        $this->areaQueueRedrawAll();
    }

    public function beginUserWindowMove()
    {
        $this->areaBeginUserWindowMove();
    }
    public function beginUserWindowResize(WindowResizeEdge $edge)
    {
        $this->areaBeginUserWindowResize($edge->value);
    }

    public function scrollTo(float $x, float $y, float $w, float $h)
    {
        $this->areaScrollTo($x, $y, $w, $h);
    }

    public function __destruct()
    {
        $this->free();
    }

    public function free()
    {
        self::$ui->ffi()::free($this->handler);
    }

    public function drawPath($config)
    {
        $config['widget'] = 'path';
        return $this->build->createItem($config);
    }

    public function newBrush()
    {
        return new Brush($this->build);
    }

    public function newMatrix()
    {
        return new Matrix($this->build);
    }

    public function newStrokeParams()
    {
        return new StrokeParams($this->build);
    }

    public function drawfill(Path $path, Brush $brush)
    {
        self::$ui->drawFill($this->context, $path->getUIInstance(), $brush->getBrush());
    }

    /**
     * draw stroke
     *
     * @param Brush $brush      is new UI\Control\Brush
     * @param StrokeParams $params  is new UI\Struct\StrokeParams
     * @return void
     */
    public function drawStroke(Path $path, Brush $brush, StrokeParams $params)
    {
        $paramType = $params->getStrokeParams();
        self::$ui->drawStroke($this->context, $path->getUIInstance(), $brush->getBrush(), $paramType);
    }

    public function drawTransform(Matrix $m)
    {
        self::$ui->drawTransform($this->context, $m->getMatrix());
    }

    public function drawClip(Path $path)
    {
        self::$ui->drawClip($this->context, $path->getUIInstance());
    }

    public function drawSave()
    {
        self::$ui->drawSave($this->context);
    }

    public function drawRestore()
    {
        self::$ui->drawRestore($this->context);
    }

    public function drawText(DrawText $d, float $x, float $y)
    {
        self::$ui->drawText($this->context, $d->getUIInstance(), $x, $y);
    }
}

<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 * @version    0.1
 */

namespace UI\Control;

use UI\Control;
use FFI\CData;
use UI\Struct\Matrix;
use UI\Control\Path;
use UI\Struct\StrokeParams;
use UI\Struct\Brush;
use UI\Struct\TextLayoutParams;
use UI\Struct\AreaDrawParams;

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

    public function newControl(): CData
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

        return self::$ui->addr($this->handler);
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

            $func = $this->attr['draw']->getFunc();
            $this->context = $params[0]->Context;

            $areaParam = new AreaDrawParams($this->build, $params[0]);

            $func($handlerArr, $this, $areaParam);
        } catch (\Error $e) {
            echo $e;
        }
    }

    public function mouseEvent($handler, $area, $mouseEvent)
    {
        try {
            if (empty($this->attr['mouseEvent'])) {
                return;
            }

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
                'held1To64' => $mouseEvent[0]->Held1To64,
            ];
            $func = $this->attr['mouseEvent']->getFunc();
            $func($handlerArr, $this, $mouseEventArr);
        } catch (\Error $e) {
            echo $e;
        }
    }

    public function mouseCrossed($handler, $area, $left)
    {
        try {
            if (empty($this->attr['mouseCrossed'])) {
                return;
            }

            $handlerArr = [
                'draw' => $handler[0]->Draw,
                'mouseEvent' => $handler[0]->MouseEvent,
                'mouseCrossed' => $handler[0]->MouseCrossed,
                'dragBroken' => $handler[0]->DragBroken,
                'keyEvent' => $handler[0]->KeyEvent
            ];
            $func = $this->attr['mouseCrossed']->getFunc();
            $func($handlerArr, $this, $left);
        } catch (\Error $e) {
            echo $e;
        }
    }

    public function dragBroken($handler, $area)
    {
        try {
            if (empty($this->attr['dragBroken'])) {
                return;
            }

            $handlerArr = [
                'draw' => $handler[0]->Draw,
                'mouseEvent' => $handler[0]->MouseEvent,
                'mouseCrossed' => $handler[0]->MouseCrossed,
                'dragBroken' => $handler[0]->DragBroken,
                'keyEvent' => $handler[0]->KeyEvent
            ];
            $func = $this->attr['mouseCrossed']->getFunc();
            $func($handlerArr, $this);
        } catch (\Error $e) {
            echo $e;
        }
    }

    public function keyEvent($handler, $area, $keyEvent)
    {
        try {
            if (empty($this->attr['keyEvent'])) {
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
            $func = $this->attr['keyEvent']->getFunc();
            $func($handlerArr, $this, $keyEventAttr);
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

    /**
     * @param int $edge  The value specify of UI\UI::WINDOW_RESIZE_EDGE_*
     */
    public function beginUserWindowResize(int $edge)
    {
        $this->uiAreaBeginUserWindowResize($edge);
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
        return new Path($this->build, $config);
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

    public function newTextLayoutParams()
    {
        return new TextLayoutParams($this->build);
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

<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;
use UI\Control\Draw\Path;

class Area extends Control
{
    const CTL_NAME = 'canvas';
    private $handler = null;
    public function newControl(): CData
    {
        $handler = $this->areaHandler();
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
        $this->handler = self::$ui->new('struct handler { uiAreaHandler ah;}', false);
        $this->handler->ah->Draw = [$this, 'draw'];
        $this->handler->ah->MouseEvent = [$this,  'mouseEvent'];
        $this->handler->ah->MouseCrossed = [$this, 'mouseCrossed'];
        $this->handler->ah->DragBroken = [$this, 'dragBroken'];
        $this->handler->ah->KeyEvent = [$this, 'keyEvent'];

        $handlerPtr = self::$ui->addr($this->handler);
        return self::$ui->cast('uiAreaHandler *', $handlerPtr);
    }

    public function draw($handler, $area, $params)
    {
        if (!$this->attr['draw']) {
            return;
        }
        $handlerArr = [
            'draw' => $handler[0]->draw,
            'mouseEvent' => $handler[0]->mouseEvent,
            'mouseCrossed' => $handler[0]->mouseCrossed,
            'dragBroken' => $handler[0]->dragBroken,
            'keyEvent' => $handler[0]->keyEvent
        ];

        $func = $this->attr['draw'];
        $paramsArr = [
            'context' => $params[0]->Context,
            'areaWidth' => $params[0]->AreaWidth,
            'areaHeight' => $params[0]->AreaHeight,
            'clipX' => $params[0]->ClipX,
            'clipY' => $params[0]->ClipY,
            'clipWidth' => $params[0]->ClipWidth,
            'clipHeight' => $params[0]->ClipHeight
        ];
        $func($handlerArr, $this, $paramsArr);
    }

    public function mouseEvent($handler, $area, $mouseEvent)
    {
        if (!$this->attr['mouseEvent']) {
            return;
        }
        $handlerArr = [
            'draw' => $handler[0]->draw,
            'mouseEvent' => $handler[0]->mouseEvent,
            'mouseCrossed' => $handler[0]->mouseCrossed,
            'dragBroken' => $handler[0]->dragBroken,
            'keyEvent' => $handler[0]->keyEvent
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
        $func = $this->attr['mouseEvent'];
        $func($handlerArr, $this, $mouseEventArr);
    }

    public function mouseCrossed($handler, $area, $left)
    {
        if (!$this->attr['mouseCrossed']) {
            return;
        }
        $handlerArr = [
            'draw' => $handler[0]->draw,
            'mouseEvent' => $handler[0]->mouseEvent,
            'mouseCrossed' => $handler[0]->mouseCrossed,
            'dragBroken' => $handler[0]->dragBroken,
            'keyEvent' => $handler[0]->keyEvent
        ];
        $func = $this->attr['mouseCrossed'];
        $func($handlerArr, $this, $left);
    }

    public function dragBroken($handler, $area)
    {
        if (!$this->attr['dragBroken']) {
            return;
        }
        $handlerArr = [
            'draw' => $handler[0]->draw,
            'mouseEvent' => $handler[0]->mouseEvent,
            'mouseCrossed' => $handler[0]->mouseCrossed,
            'dragBroken' => $handler[0]->dragBroken,
            'keyEvent' => $handler[0]->keyEvent
        ];
        $func = $this->attr['mouseCrossed'];
        $func($handlerArr, $this);
    }

    public function keyEvent($handler, $area, $keyEvent)
    {
        if (!$this->attr['keyEvent']) {
            return;
        }
        $handlerArr = [
            'draw' => $handler[0]->draw,
            'mouseEvent' => $handler[0]->mouseEvent,
            'mouseCrossed' => $handler[0]->mouseCrossed,
            'dragBroken' => $handler[0]->dragBroken,
            'keyEvent' => $handler[0]->keyEvent
        ];
        $keyEventAttr = [
            'key' => $keyEvent[0]->Key,
            'extKey' => $keyEvent[0]->ExtKey,
            'modifier' => $keyEvent[0]->modifier,
            'modifiers' => $keyEvent[0]->modifiers,
            'up' => $keyEvent[0]->Up,
        ];
        $func = $this->attr['keyEvent'];
        $func($handlerArr, $this, $keyEventAttr);
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
}

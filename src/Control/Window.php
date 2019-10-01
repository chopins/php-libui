<?php

namespace UI\Control;

use UI\Control;

class Window extends Control
{
    public static $defWinWidth = 800;
    public static $defWinHeight = 640;
    public static $defWinTitle = 'No Win Title';
    public function newControl()
    {
        $err = self::$ui->init();
        if ($err) {
            throw new ErrorException($err);
        }
        $this->attr['title'] = $this->attr['title'] ?? self::$defWinTitle;
        $this->attr['width'] = $this->attr['width'] ?? self::$defWinWidth;
        $this->attr['height'] = $this->attr['height'] ?? self::$defWinHeight;
        $this->instance = self::$ui->newWindow($this->attr['title'], $this->attr['width'], $this->attr['height'], $this->attr['hasMenu']);
        if (isset($this->attr['border'])) {
            self::$ui->windowSetBorderless($this->instance, $this->attr['border']);
        }
        if (isset($this->attr['margin'])) {
            self::$ui->windowSetMargined($this->attr['margin']);
        }
        if (isset($this->attr['quit'])) {
            $this->onQuit($this->attr['quit']);
        }
        if (isset($this->attr['close'])) {
            $this->onClose($this->attr['close']);
        }
        if (isset($this->attr['resize'])) {
            $this->onResize($this->attr['resize']);
        }
    }

    public function onQuit(array $callable)
    {
        $this->bindEvent('onShouldQuit', $callable);
    }

    public function onClose(array $callable)
    {
        $this->bindEvent('windowOnClosing', $callable);
    }

    public function onResize(array $callable)
    {
        $this->bindEvent('windowOnContentSizeChanged', $callable);
    }


    public function title($title = null)
    {
        if ($title === null) {
            return self::$ui->string($this->windowTitle());
        }
        $this->windowSetTitle($title);
    }

    public function border($border = null)
    {
        if ($border === null) {
            return $this->windowBorderless();
        }
        $this->windowSetBorderless($border);
    }

    public function margin($margin = null)
    {
        if ($margin === null) {
            return $this->windowMargined();
        }
        $this->windowSetMargined($margin);
    }

    public function setChild(Control $child)
    {
        $uiControl = $child->getUIInstance();
        $this->windowSetChild($uiControl);
    }

    public function winSize($size = null)
    {
        if ($size === null) {
            $w = self::$ui->ffi()->new('int*');
            $h = self::$ui->ffi()->new('int*');
            $this->uiWindowContentSize($w, $h);
            return ['w' => $w->cdata, 'h' => $h->cdata];
        }
        $this->windowSetContentSize($size['w'], $size['h']);
    }
    public function fullscreen($isFull = null)
    {
        if ($isFull === null) {
            return $this->windowFullscreen();
        }
        return $this->windowSetFullscreen($isFull);
    }
}

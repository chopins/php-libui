<?php

namespace UI\Control;

use FFI\CData;
use UI\Control;

/**
 * @method void msgBox(string $title, string $msg)
 * @method void msgBoxError(string $title, string $msg)  error box
 * @method string openFile() open file box
 * @method string saveFile() save file box
 * @method string windowTitle()  get window title
 * @method string windowSetTitle(string $title)   set window title
 * @method void windowSetContentSize(int $width, int $height)
 * @method int windowFullscreen()
 * @method void windowSetFullscreen(int $fullscreen)
 * @method void windowOnContentSizeChanged(callable $func, $data)
 * @method void windowOnClosing(callable $func, $data)
 * @method int windowBorderless()
 * @method void windowSetBorderless(int $borderless)
 * @method void windowSetChild(FFI\CData $child)
 * @method int windowMargined()
 * @method void windowSetMargined(int $margin)
 * 
 */
class Window extends Control
{
    public static $defWinWidth = 800;
    public static $defWinHeight = 640;
    public static $defWinTitle = 'No Win Title';
    public function newControl():CData
    {
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
        return $this->instance;
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
    
    public function windowContentSize(int &$width, int &$height) {
        $w = self::$ui->new('int*');
        $h = self::$ui->new('int*');
        self::$ui->windowContentSize($this->instance, $w, $h);
        $width = $w[0];
        $height = $h[0];
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

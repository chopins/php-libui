<?php

namespace UI\Control;

use UI\Control;

/**
 * Create button,file,save-file,font,color by type in config
 */
class Button extends Control
{
    public function newControl()
    {
        $type = $this->attr['type'] ?? null;
        $this->attr['click'] = $this->attr['click'] ?? null;
        switch ($type) {
            case 'file':
                $this->instance = self::$ui->newButton($this->attr['title']);
                $this->onclick([function ($c, $d) {
                    $filename = self::$ui->string(self::$ui->openFile($this->win));
                    $call = $this->attr['click'][0];
                    $data = $this->attr['click'][1] ?? null;
                    $call($c, $filename, $data);
                }]);
                break;
            case 'save':
                $this->instance = self::$ui->newButton($this->attr['title']);
                $this->onclick([function ($c, $d) {
                    $filename = self::$ui->string(self::$ui->saveFile($this->win));
                    $call = $this->attr['click'][0];
                    $data = $this->attr['click'][1] ?? null;
                    $call($c, $filename,  $data);
                }]);
                break;
            case 'font':
                $this->instance = self::$ui->newFontButton();
                if ($this->attr['change']) {
                    $this->onchage($this->attr['change']);
                }
                break;
            case 'color':
                $this->instance = self::$ui->newColorButton();
                if ($this->attr['click']) {
                    $this->onchange($this->attr['click']);
                }
            case 'button':
            default:
                $this->instance = self::$ui->newButton($this->attr['title']);
                if ($this->attr['click']) {
                    $this->onclick($this->attr['click']);
                }
        }
        return $this->instance;
    }

    public function onclick(array $callable)
    {
        if ($this->attr['type'] !== 'font' && $this->attr['type'] !== 'color') {
            $this->bindEvent('buttonOnClicked', $callable);
        }
    }

    public function onchange(array $callable)
    {
        if ($this->attr['type'] == 'font') {
            $this->bindEvent('fontButtonOnChanged', $callable);
        } elseif ($this->attr['type'] === 'color') {
            $this->bindEvent('colorButtonOnChanged', $callable);
        }
    }

    public function getValue()
    {
        return $this->buttonText();
    }

    public function setValue($text)
    {
        $this->buttonSetText($text);
    }
}

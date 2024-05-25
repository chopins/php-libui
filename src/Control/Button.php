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
use UI\Event;
use UI\Struct\FontDescriptor;

/**
 * Create button,file,save-file,font,color by type in config
 * 
 * @property-read string $type
 * @property \UI\Event $click
 * @property \UI\Event $change
 * @property-read string $title
 */
class Button extends Control
{
    const CTL_NAME = 'button';

    protected function newControl(): CData
    {
        $type = $this->attr['type'] ?? null;
        $this->attr['click'] = $this->attr['click'] ?? null;
        $this->attr['change'] = $this->attr['change'] ?? null;
        switch ($type) {
            case 'file':
                $this->instance = self::$ui->newButton($this->attr['title']);
                if ($this->attr['click']) {
                    $this->onClick($this->attr['click']);
                }
                break;
            case 'save':
                $this->instance = self::$ui->newButton($this->attr['title']);
                if ($this->attr['click']) {
                    $this->onClick($this->attr['click']);
                }
                break;
            case 'font':
                $this->instance = self::$ui->newFontButton();
                if ($this->attr['change']) {
                    $this->onChage($this->attr['change']);
                }
                break;
            case 'color':
                $this->instance = self::$ui->newColorButton();
                if ($this->attr['click']) {
                    $this->onChange($this->attr['click']);
                }
                break;
            case 'button':
            default:
                $this->instance = self::$ui->newButton($this->attr['title']);
                if ($this->attr['click']) {
                    $this->onClick($this->attr['click']);
                }
        }
        return $this->instance;
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'click':
                $this->onClick($value);
                break;
            case 'change':
                $this->onChange($value);
                break;
        }
    }

    public function onClick(Event $callable)
    {
        $this->attr['click'] = $callable;
        switch ($this->attr['type']) {
            case 'file':
                $callable->onBefore(function () {
                    return $this->build->openFile();
                }, 'file');
                break;
            case 'save':
                $callable->onBefore(function () {
                    return $this->build->saveFile();
                }, 'file');
                break;
        }
        $this->bindEvent('buttonOnClicked', $callable);
    }

    public function onChange(Event $callable)
    {
        $this->attr['change'] = $callable;
        switch ($this->attr['type']) {
            case 'font':
                $this->bindEvent('fontButtonOnChanged', $callable);
                break;
            case 'color':
                $this->bindEvent('colorButtonOnChanged', $callable);
                break;
        }
    }

    public function getValue()
    {
        switch ($this->attr['type']) {
            case 'font':
                $fontDes = new FontDescriptor($this->build);
                $fs = $fontDes->value();
                $this->fontButtonFont($fs);
                return $fontDes;
            case 'color':
                $r = self::$ui->new('double*');
                $g = self::$ui->new('double*');
                $bl = self::$ui->new('double*');
                $a = self::$ui->new('double*');
                $this->colorButtonColor($r, $g, $bl, $a);
                return [
                    'red' => $r[0], 'green' => $g[0], 'blue' => $bl[0], 'alpha' => $a[0]
                ];
            default:
                return $this->buttonText();
        }
    }

    public function setValue($text)
    {
        switch ($this->attr['type']) {
            case 'font':
                break;
            case 'color':
                $this->colorButtonSetColor($text['red'], $text['green'], $text['blue'], $text['alpha']);
                break;
            default:
                $this->buttonSetText($text);
                break;
        }
    }
}

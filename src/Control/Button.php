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
        if ($this->attr['type'] === 'font') {
            $des = self::$ui->addr(self::$ui->new('uiFontDescriptor'));
            $this->fontButtonFont($des);
            $fonts = [
                'family' => self::$ui::string($des[0]->Family),
                'size' => $des[0]->Size,
                'weight' => $des[0]->Weight,
                'italic' => $des[0]->Italic,
                'stretch' => $des[0]->Stretch,
                'cdata' => $des
            ];
            return $fonts;
        } elseif ($this->attr['type'] === 'color') {
            $r = self::$ui->new('double*');
            $g = self::$ui->new('double*');
            $bl = self::$ui->new('double*');
            $a = self::$ui->new('double*');
            $this->colorButtonColor($r, $g, $bl, $a);
            return [
                'red' => $r[0], 'green' => $g[0], 'blue' => $bl[0], 'alpha' => $a[0]
            ];
        } else {
            return $this->buttonText();
        }
    }

    public function freeFont(array $fonts)
    {
        self::$ui->freeFontButtonFont($fonts['cdata']);
    }

    public function setValue($text)
    {
        if ($this->attr['type'] === 'font') { } elseif ($this->attr['type'] === 'color') {
            $this->colorButtonSetColor($text['red'], $text['green'], $text['blue'], $text['alpha']);
        } else {
            $this->buttonSetText($text);
        }
    }
}

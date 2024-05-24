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

/**
 * @property-read string $dir
 * @property int $padded
 * @property-read int $child_fit
 */
class Box extends Control
{
    const CTL_NAME = 'box';

    protected function newControl(): CData
    {
        $this->attr['dir'] = $this->attr['dir'] ?? 'h';
        $this->attr['padded'] = $this->attr['padded'] ?? 0;
        $this->attr['child_fit'] = $this->attr['child_fit'] ?? 1;
        if ($this->attr['dir'] == 'v') {
            $this->instance = self::$ui->newVerticalBox();
        } else {
            $this->instance = self::$ui->newHorizontalBox();
        }
        $this->boxSetPadded($this->attr['padded']);
        return $this->instance;
    }

    public function __set($name, $value)
    {
        if ($name === 'padded') {
            $this->setPadded($value);
        }
    }

    public function addChild(\UI\Control $childs, $option = [])
    {
        $fit = $option['child_fit'] ?? $this->attr['child_fit'];
        $this->append($childs, $fit);
    }

    public function setPadded(int $padded)
    {
        $this->attr['padded'] = $padded;
        $this->boxSetPadded($padded);
    }

    public function getPadded(): int
    {
        return $this->boxPadded();
    }

    public function append(Control $control, int $stretchy)
    {
        $ui = $control->getUIInstance();
        $this->boxAppend($ui, $stretchy);
    }

    public function delete($idx)
    {
        $this->boxDelete($idx);
    }

}

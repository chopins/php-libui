<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

class Box extends Control
{
    const CTL_NAME = 'box';
    public function newControl(): CData
    {
        $this->attr['dir'] = $this->attr['dir'] ?? 'h';
        $this->attr['padded'] = $this->attr['padded'] ?? 0;
        if ($this->attr['dir'] == 'v') {
            $this->instance = self::$ui->newVerticalBox();
        } else {
            $this->instance = self::$ui->newHorizontalBox();
        }
        $this->boxSetPadded($this->attr['padded']);
        return $this->instance;
    }

    public function addChild(\UI\Control $childs)
    {
        $fit = $this->attr['child_fit'] ?? 0;
        $this->append($childs, $fit);
    }
    public function setPadded(int $padded)
    {
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

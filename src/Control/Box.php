<?php

namespace UI\Control;

use UI\Control;

class Box extends Control
{
    public function newControl()
    {
        $this->attr['dir'] = $this->attr['dir'] ?? 'h';
        $this->attr['padded'] = $this->attr['padded'] ?? 0;
        if ($this->attr['dir'] == 'v') {
            $this->instance = self::$ui->newVerticalBox();
        } else {
            $this->instance = self::$ui->newHorizontalBox();
        }
        $this->boxSetPadded($this->attr['padded']);
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

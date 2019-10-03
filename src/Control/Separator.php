<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

class Separator extends Control
{
    public function newControl(): CData
    {
        if ($this->attr['type'] == 'hr') {
            $this->instance = self::$ui->newHorizontalSeparator();
        } elseif ($this->att['type'] == 'vr') {
            $this->instance = self::$ui->newVerticalSeparator();
        }
        return $this->instance;
    }
}

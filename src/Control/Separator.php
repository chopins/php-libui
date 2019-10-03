<?php

namespace UI\Control;

use UI\Control;

class Separator extends Control
{
    public function newControl()
    {
        if ($this->attr['type'] == 'hr') {
            $this->instance = self::$ui->newHorizontalSeparator();
        } elseif ($this->att['type'] == 'vr') {
            $this->instance = self::$ui->newVerticalSeparator();
        }
    }
}

<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

class Progress extends Control
{
    const CTL_NAME = 'progress';
    public function newControl(): CData
    {
        $this->instance = self::$ui->newProgressBar();
        return $this->instance;
    }
    public  function setValue(int $v)
    {
        $this->progressBarSetValue($v);
    }

    public  function getValue(): int
    {
        return $this->progressBarValue();
    }
}

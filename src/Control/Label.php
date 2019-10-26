<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

/**
 * @property-read string $title
 */
class Label extends Control
{
    const CTL_NAME = 'label';
    public function newControl(): CData
    {
        $this->instance = self::$ui->newLabel($this->attr['title']);
        return $this->instance;
    }
    public function getTitle()
    {
        return $this->labelText();
    }

    public function setTitle($title)
    {
        $this->labelSetText($title);
    }
}

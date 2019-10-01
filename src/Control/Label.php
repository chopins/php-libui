<?php

namespace UI\Control;

use UI\Control;

class Label extends Control
{
    public function newControl()
    {
        $this->instance = self::$ui->newLabel($this->attr['title']);
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

<?php

namespace UI\Control;

use FFI\CData;
use UI\Control;

class Form extends Control
{
    public function newControl(): CData
    {
        $this->instance = self::$ui->newForm();
        $this->setPadded($this->attr['padded']);
        return $this->instance;
    }

    public function addChilds(\UI\Control $childs)
    {
        $this->append($childs);
    }

    public function setPadded(int $padded)
    {
        $this->formSetPadded($padded);
    }

    public function getPadded()
    {
        return $this->formPadded();
    }

    public function append(Control $child)
    {
        $ui = $child->getUIInstance();
        $this->formAppend($ui);
    }

    public function delete(int $idx)
    {
        $this->formDelete($idx);
    }
}

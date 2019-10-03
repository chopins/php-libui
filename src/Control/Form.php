<?php

namespace UI\Control;

use UI\Control;

class Form extends Control
{
    public function newControl()
    {
        $this->instance = self::$ui->newForm();
        $this->setPadded($this->attr['padded']);
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

<?php

namespace UI\Control;

use UI\Control;

class Grid extends Control
{
    public function newControl()
    {
        $this->instance = self::$ui->newGrid();
        $this->setPadded($this->attr['padded']);
    }

    public function setPadded(int $padded)
    {
        $this->gridSetPadded($padded);
    }

    public function addChilds(\UI\Control $childs)
    {
        $config = $childs->getAttr();
        $this->append($childs, $config['left'], $config['top'], $config['width'], $config['height'], $config['hexpand'], $config['haligin'], $config['vexpand'], $config['valign']);
    }

    public function getPadded()
    {
        return $this->gridPadded();
    }

    public function append(Control $child, int $left, int $top, int $xspan, int $yspan, int $hexpand, int $halign, int $vexpand, int $valign)
    {
        $ui = $child->getUIInstance();
        $this->gridAppend($ui, $left, $top, $xspan, $yspan, $hexpand, $halign, $vexpand, $valign);
    }

    public function insert(Control $child, Control $exist, int $at, int $xspan, int $yspan, int $hexpand, int $halign, int $vexpand, int $valign)
    {
        $ui = $child->getUIInstance();
        $eui = $exist->getUIInstance();
        $this->gridInsertAt($ui, $eui, $at, $xspan, $yspan, $hexpand, $halign, $vexpand, $valign);
    }
}

<?php

namespace UI\Control;

use FFI\CData;
use UI\Control;

/**
 * @property int $padded
 * @property-read int $child_left
 * @property-read int $child_top
 * @property-read int $child_width
 * @property-read int $child_height
 * @property-read int $child_hexpand
 * @property-read int $child_haligin
 * @property-read int $child_vexpand
 * @property-read int $child_valign
 */
class Grid extends Control
{
    const CTL_NAME = 'grid';
    public function newControl(): CData
    {
        $this->instance = self::$ui->newGrid();
        $this->setPadded($this->attr['padded']);
        return $this->instance;
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'padded':
                $this->setPadded($value);
                break;
        }
    }

    public function setPadded(int $padded)
    {
        $this->attr['padded'] = $padded;
        $this->gridSetPadded($padded);
    }

    protected function addChild(\UI\Control $childs)
    {
        $this->append($childs, $this->attr['child_left'], $this->attr['child_top'], $this->attr['child_width'], $this->attr['child_height'], $this->attr['child_hexpand'], $this->attr['child_haligin'], $this->attr['child_vexpand'], $this->attr['child_valign']);
    }

    public function getPadded()
    {
        return $this->gridPadded();
    }

    protected function append(Control $child, int $left, int $top, int $xspan, int $yspan, int $hexpand, int $halign, int $vexpand, int $valign)
    {
        $ui = $child->getUIInstance();
        $this->gridAppend($ui, $left, $top, $xspan, $yspan, $hexpand, $halign, $vexpand, $valign);
    }

    public function insert(Control $child, Control $exist, int $at, int $xspan, int $yspan, int $hexpand, int $halign, int $vexpand, int $valign)
    {
        $ui = $child->getUIInstance();
        $eui = $exist->getUIInstance();
        $this->gridInsertAt($ui, $eui, $at, $xspan, $yspan, $hexpand, $halign, $vexpand, $valign);
        $this->updateChildsList($child);
    }
}

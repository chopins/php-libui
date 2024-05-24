<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 * @version    0.1
 */

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
    protected $children =  0;

    protected array $attrList = ['child_left', 'child_top',
        'child_width', 'child_height', 'child_hexpand',
        'child_halign', 'child_vexpand', 'child_valign'];

    protected function newControl(): CData
    {
        foreach ($this->attrList as $k) {
            $this->initAttr($this->attr, $k, 0);
        }
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

    public function initAttr(&$arr, $key, $def = null, $defArr = [])
    {
        if ($def === null) {
            $arr[$key] = $arr[$key] ?? $defArr[$key];
        } else {
            $arr[$key] = $arr[$key] ?? $def;
        }
    }

    protected function addChild(Control $childs, $option = [])
    {
        foreach ($this->attrList as $k) {
            $this->initAttr($option, $k, null, $this->attr);
        }
        $this->append(
                $childs,
                $option['child_left'],
                $option['child_top'],
                $option['child_width'],
                $option['child_height'],
                $option['child_hexpand'],
                $option['child_halign'],
                $option['child_vexpand'],
                $option['child_valign']);
    }

    public function getPadded()
    {
        return $this->gridPadded();
    }

    protected function append(Control $child, int $left, int $top, int $xspan, int $yspan, int $hexpand, int $halign, int $vexpand, int $valign)
    {
        $ui = $child->getUIInstance();
        $this->gridAppend($ui, $left, $top, $xspan, $yspan, $hexpand, $halign, $vexpand, $valign);
        $this->children++;
    }

    public function insert(Control $child, Control $exist, int $at, int $xspan, int $yspan, int $hexpand, int $halign, int $vexpand, int $valign)
    {
        $ui = $child->getUIInstance();
        $eui = $exist->getUIInstance();
        $this->gridInsertAt($ui, $eui, $at, $xspan, $yspan, $hexpand, $halign, $vexpand, $valign);
        $this->children++;
        $this->updateChildsList($child);
    }

}

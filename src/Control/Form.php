<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

namespace UI\Control;

use FFI\CData;
use UI\Control;

/**
 * @property int $padded
 */
class Form extends Control
{
    const CTL_NAME = 'form';
    protected function newControl(): CData
    {
        $this->instance = self::$ui->newForm();
        $this->setPadded($this->attr['padded']);
        return $this->instance;
    }

    protected function prepareOption()
    {
        $stretchy = $this->attr['stretchy'] ?? 0;
        foreach ($this->attr['childs'] as $i => &$child) {
            $child['stretchy'] = $child['stretchy'] ?? $stretchy;
            $child['label'] = $child['label'] ?? $i;
        }
    }

    protected function addChild(Control $child, $option = [])
    {
        $this->appendControl($option['label'], $child, $option['stretchy']);
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
        $this->formSetPadded($padded);
    }

    public function getPadded()
    {
        return $this->formPadded();
    }

    protected function append(Control $child, $options = null)
    {
        $this->appendControl($options['label'], $child, $options['stretchy']);
    }

    protected function appendControl(string $label, Control $child, int $stretchy)
    {
        $control = $child->getUIInstance();
        $this->formAppend($label, $control, $stretchy);
    }

    public function delete(int $idx)
    {
        $this->formDelete($idx);
    }
}

<?php

namespace UI\Control;

use FFI\CData;
use UI\Control;

/**
 * @property int $padded
 */
class Form extends Control
{
    const CTL_NAME = 'form';

    public function newControl(): CData
    {
        $this->instance = self::$ui->newForm();
        $this->setPadded($this->attr['padded']);
        return $this->instance;
    }

    public function pushChilds()
    {
        $this->attr['childs'] = $this->attr['childs'] ?? [];
        $allStretchy = $this->attr['stretchy'] ?? 0;
        foreach ($this->attr['childs'] as $label => $child) {
            $itemStretchy = $child['stretchy'] ?? $allStretchy;

            foreach ($child as $k => $sub) {
                if ($k === 'stretchy') {
                    continue;
                }
                $control = $this->build->createItem($sub['name'], $sub['attr']);
                $this->addChild($control, ['label' => $label, 'stretchy' => $sub['stretchy'] ?? $itemStretchy]);
            }
        }
    }

    protected function addChild(Control $child, $option = [])
    {
        $this->append($option['label'], $child, $option['stretchy']);
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

    public function append(string $label, Control $child, int $stretchy)
    {
        $control = $child->getUIInstance();
        $this->formAppend($label, $control, $stretchy);
    }

    public function delete(int $idx)
    {
        $this->formDelete($idx);
    }

}

<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

/**
 * @property-read string $title
 * @property int $margin
 */
class Group extends Control
{
    const CTL_NAME = 'group';

    public function newControl(): CData
    {
        $this->attr['title'] = $this->attr['title'] ?? '';
        $this->attr['margin'] = $this->attr['margin'] ?? 0;
        $this->instance = self::$ui->newGroup($this->attr['title']);
        $this->setMargin($this->attr['margin']);
        return $this->instance;
    }

    protected function addChild(\UI\Control $childs, $option = [])
    {
        $this->setChild($childs);
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'margin':
                $this->setMargin($value);
                break;
        }
    }

    public function setMargin(int $margin)
    {
        $this->attr['margin'] = $margin;
        $this->groupSetMargined($margin);
    }

    public function getMargin(): int
    {
        return $this->groupMargined();
    }

    protected function setChild(Control $child)
    {
        $ui = $child->getUIInstance();
        $this->groupSetChild($ui);
    }

    public function getTitle()
    {
        return $this->groupTitle();
    }

    public function setTitle($title)
    {
        $this->groupSetTitle($title);
    }

}

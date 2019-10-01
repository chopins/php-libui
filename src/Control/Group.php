<?php

namespace UI\Control;

use UI\Control;

class Group extends Control
{

    public function newControl()
    {
        $this->attr['title'] = $this->attr['title'] ?? '';
        $this->attr['margin'] = $this->attr['margin'] ?? 0;
        $this->instance = self::$ui->newGroup($this->attr['title']);
        $this->setMargin($this->attr['margin']);
    }

    public function setMargin(int $margin)
    {
        $this->groupSetMargined($margin);
    }

    public function getMargin(): int
    {
        return $this->groupMargined();
    }

    public function setChild(Control $child)
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

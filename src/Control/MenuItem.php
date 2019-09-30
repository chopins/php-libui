<?php

namespace UI\Control;

use UI\Control\Menu;

class MenuItem extends Menu
{
    public function newControl()
    {
        $parent  = $this->attr['parent'];
        if ($this->attr['type'] == 'checkbox') {
            $this->instance = self::$ui->menuAppendCheckItem($parent->getUIInstance(), $this->attr['title']);
        } else {
            $this->instance = self::$ui->menuAppendItem($this->attr['parent'], $this->attr['title']);
        }
        if (isset($this->attr['childs'])) {
            $this->buildSubMenu($this, $this->attr['childs']);
        }
        if (isset($this->attr['click'])) {
            $this->onclick($this->attr['click']);
        }
    }

    public function onclick(array $callable)
    {
        $this->bindEvent('menuItemOnClicked', $callable);
    }
}

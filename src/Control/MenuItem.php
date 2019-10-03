<?php

namespace UI\Control;

use UI\Control\Menu;
use FFI\CData;

class MenuItem extends Menu
{
    public function newControl(): CData
    {
        $parent  = $this->attr['parent'];
        $this->attr['id'] = $this->attr['id'] ?? $this->attr['parent_id'] . '_item_' . ($this->attr['idx'] - 1);
        $this->attr['type'] = $this->attr['type'] ?? 'text';
        if ($this->attr['type'] == 'checkbox') {
            $this->instance = self::$ui->menuAppendCheckItem($parent->getUIInstance(), $this->attr['title']);
        } else {
            $this->instance = self::$ui->menuAppendItem($parent->getUIInstance(), $this->attr['title']);
        }
        if (isset($this->attr['click'])) {
            $this->onclick($this->attr['click']);
        }
        return $this->instance;
    }

    public function onclick(array $callable)
    {
        $this->bindEvent('menuItemOnClicked', $callable);
    }
}

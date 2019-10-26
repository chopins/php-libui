<?php

namespace UI\Control;

use FFI\CData;
use RuntimeException;
use UI\Control;
use UI\Control\MenuItem;

/**
 * Create window menu
 * 
 * @property-read string $title
 */
class Menu extends Control
{
    const CTL_NAME = 'menu';

    protected $childs = [];

    public function newControl(): CData
    {
        static $i = 0;
        if (empty($this->attr['title'])) {
            throw new RuntimeException('menu title can not empty');
        }
        $this->attr['id'] = $this->attr['id'] ?? '_win_menu_' . $i;
        $i++;
        $this->instance = self::$ui->newMenu($this->attr['title']);
        return $this->instance;
    }

    public function pushChilds()
    {
        $this->attr['childs'] = $this->attr['childs'] ?? [];
        foreach ($this->attr['childs'] as $child) {
            if (is_array($child)) {
                $this->childs[] = $this->addMenuItem($child);
            } else if ($child == 'hr') {
                $this->addSep();
            }
        }
    }

    /**
     * 
     * @param array $menus
     * @return MenuItem
     */
    public function addMenuItem(array $menus): MenuItem
    {
        $menus['parent'] = $this;
        $menus['parent_id'] = $this->attr['id'];
        $menus['idx'] = count($this->childs);
        $item = new MenuItem($this->build, $menus);
        $this->childs[] = $item;
        return $item;
    }

    public function addSep()
    {
        self::$ui->menuAppendSeparator($this->getUIInstance());
    }

    public function getChilds()
    {
        return $this->childs;
    }

    public function getHandle()
    {
        return $this->attr['id'];
    }

    public function enable()
    {
        return $this->menuItemEnable();
    }

    public function disbale()
    {
        return $this->menuItemDisable();
    }

}

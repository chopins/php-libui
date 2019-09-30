<?php

namespace UI\Control;

use UI\Control;
use UI\Control\MenuItem;

/**
 * Create window menu
 */
class Menu extends Control
{
    protected $childs = [];

    public function newControl()
    {
        foreach ($this->attr as  $item) {
            $nm = $this->newMenu($item['label']);
            $this->newControl($nm, 'menu', $item);
            if (isset($item['childs'])) {
                $this->buildSubMenu($nm, $item['childs']);
            }
        }
    }


    public function buildSubMenu($parent, $menus)
    {
        foreach ($menus as $child) {
            if (is_array($child)) {
                $this->childs[] = $this->addMenuItem($child);
            } else if ($child == 'hr') {
                $this->addSep();
            }
        }
    }

    public function addMenuItem($menus)
    {
        $menus['parent'] = $this;
        return new MenuItem($this->build, $menus);
    }

    public function addSep()
    {
        self::$ui->menuAppendSeparator($this->getUIInstance());
    }

    public function getChilds()
    {
        return $this->childs;
    }
}

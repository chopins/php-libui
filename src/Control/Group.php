<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

namespace UI\Control;

use UI\Control;
use FFI\CData;

/**
 * @property-read string $title
 * @property int $margin
 * @property-read aray $child
 */
class Group extends Control
{
    const CTL_NAME = 'group';

    protected function newControl(): CData
    {
        $this->attr['title'] = $this->attr['title'] ?? '';
        $this->attr['margin'] = $this->attr['margin'] ?? 0;
        $this->instance = self::$ui->newGroup($this->attr['title']);
        $this->setMargin($this->attr['margin']);
        return $this->instance;
    }

    protected function prepareOption()
    {
        $this->attr['child'] = $this->attr['child'] ?? [];
        if ($this->attr['child']) {
            $this->attr['childs'] = [
                $this->attr['child']
            ];
        }
    }

    protected function addChild(\UI\Control $child, $option = [])
    {
        $this->setChild($child);
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

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

use UI\Control\Menu;
use FFI\CData;
use UI\Event;

/**
 * @property-read Menu $parent
 * @property-read string $type
 * @property-read \UI\Event $click
 */
class MenuItem extends Menu
{

    public function newControl(): CData
    {
        $parent = $this->attr['parent'];
        $this->attr['type'] = $this->attr['type'] ?? 'text';
        switch ($this->attr['type']) {
            case 'checkbox':
                $this->instance = self::$ui->menuAppendCheckItem($parent->getUIInstance(), $this->attr['title']);
                break;
            case 'quit':
                $this->instance = self::$ui->menuAppendQuitItem($parent->getUIInstance());
                break;
            case 'about':
                $this->instance = self::$ui->menuAppendAboutItem($parent->getUIInstance());
                break;
            case 'preferences':
                $this->instance = self::$ui->menuAppendPreferencesItem($parent->getUIInstance());
                break;
            default:
                $this->instance = self::$ui->menuAppendItem($parent->getUIInstance(), $this->attr['title']);
        }
        if (isset($this->attr['click'])) {
            $this->onClick($this->attr['click']);
        }
        $this->handle = $this->attr['parent_id'] . spl_object_id($this->instance);
        return $this->instance;
    }

    public function onClick(Event $callable)
    {
        if ($this->attr['type'] == 'file') {
            $callable->onBefore(function () {
                return $this->build->openFile();
            }, 'file');
        } else if ($this->attr['type'] == 'save') {
            $callable->onBefore(function () {
                return $this->build->saveFile();
            }, 'file');
        }
        $this->bindEvent('menuItemOnClicked', $callable);
    }

    public function isCheck()
    {
        return $this->menuItemChecked();
    }

    public function setCheck(int $check = 1)
    {
        $this->menuItemSetChecked($check);
    }
}

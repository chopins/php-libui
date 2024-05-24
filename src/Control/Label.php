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

use UI\Control;
use FFI\CData;

/**
 * @property-read string $title
 */
class Label extends Control
{
    const CTL_NAME = 'label';

    protected function newControl(): CData
    {
        $this->instance = self::$ui->newLabel($this->attr['title']);
        return $this->instance;
    }

    public function getTitle()
    {
        return $this->labelText();
    }

    public function setTitle($title)
    {
        $this->labelSetText($title);
    }

}

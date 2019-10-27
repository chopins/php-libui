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
 * @property-read string $type  value is 'hr' or 'vr'
 */
class Separator extends Control
{
    const CTL_NAME = 'sep';

    public function newControl(): CData
    {
        if ($this->attr['type'] == 'hr') {
            $this->instance = self::$ui->newHorizontalSeparator();
        } elseif ($this->attr['type'] == 'vr') {
            $this->instance = self::$ui->newVerticalSeparator();
        }
        return $this->instance;
    }

}

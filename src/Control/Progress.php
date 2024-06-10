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

class Progress extends Control
{
    const CTL_NAME = 'progress';

    protected function newControl(): CData
    {
        $this->instance = self::$ui->newProgressBar();
        return $this->instance;
    }

    public function setValue(int $v)
    {
        $this->progressBarSetValue($v);
    }

    public function getValue(): int
    {
        return $this->progressBarValue();
    }

}

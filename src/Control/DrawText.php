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
 * @property-read \UI\Struct\TextLayoutParams $params
 */
class DrawText extends Control
{
    const CTL_NAME = 'text';
    const IS_CONTROL = false;

    public function newControl(): CData
    {
        $this->instance = self::$ui->drawNewTextLayout($this->attr['params']->getParams());
        return $this->instance;
    }

    public function free()
    {
        $this->drawFreeTextLayout();
    }

    public function extents(&$width, &$height)
    {
        $wptr = self::$ui->new('double*');
        $hptr = self::$ui->new('double*');
        $this->drawTextLayoutExtents($wptr, $hptr);
        $width = $wptr[0];
        $height = $hptr[0];
    }

}

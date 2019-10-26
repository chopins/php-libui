<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

/**
 * @property-read \UI\Struct\TextLayoutParams $params
 */
class DrawText extends Control
{
    const CTL_NAME = 'text';
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

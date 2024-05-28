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
use UI\UIBuild;
use UI\Struct\AreaDrawParams;
use UI\Struct\TextLayoutParams;
use UI\Control\AttributeString;
use UI\Struct\AttributeType;
use UI\Struct\DrawTextAlign;
use UI\Struct\FontDescriptor;

/**
 * @property-read \UI\Struct\TextLayoutParams $params
 */
class DrawText extends Control
{
    const CTL_NAME = 'text';
    const IS_CONTROL = false;

    protected TextLayoutParams $layoutParams;

    protected function newControl(): CData
    {
        $font = new FontDescriptor($this->build);
        $fontAttr = $this->attr['fonts'];
        $font->fill($fontAttr['family'], $fontAttr['size'], $fontAttr['weight'], $fontAttr['italic'], $fontAttr['stretch']);
        $string = $this->build->createItem(['widget' => 'string', ...$this->attr]);
        $this->layoutParams = new TextLayoutParams($this->build, $string, $font, $this->attr['width'], $this->attr['align'],);
        $this->instance = self::$ui->drawNewTextLayout($this->layoutParams->value());
        return $this->instance;
    }

    public static function newFromParams(UIBuild $build, TextLayoutParams $params)
    {
        $ins = self::$ui->drawNewTextLayout($params->value());
        $draw = new static($build, ['string' => ''], $ins);
        $draw->layoutParams = $params;
        return $draw;
    }

    public function getParams()
    {
        return $this->layoutParams;
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

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
use UI\Struct\DrawTextAlign;
use UI\Struct\FontDescriptor;

/**
 * @property-read \UI\Struct\TextLayoutParams $params
 */
class DrawText extends Control
{
    const CTL_NAME = 'text';
    const IS_CONTROL = false;

    protected function newControl(): CData
    {
        $font = new FontDescriptor($this->build);
        $fontAttr = $this->attr['fonts'];
        $font->fill($fontAttr['family'], $fontAttr['size'], $fontAttr['weight'], $fontAttr['italic'], $fontAttr['stretch']);
        $params = self::newLayoutParams($this->build, $this->attr['string'], $this->attr['width'], $this->attr['align'], $font);
        $this->instance = self::$ui->drawNewTextLayout($params->value());
        return $this->instance;
    }
    public static function newFromParams(UIBuild $build, TextLayoutParams $params)
    {
        $ins = self::$ui->drawNewTextLayout($params->value());
        return new static($build, [], $ins);
    }

    public static function newLayoutParams(UIBuild $build, string $text, float $width, DrawTextAlign $align, FontDescriptor $font)
    {
        $string = new AttributeString($build, ['string' => $text]);
        $params = new TextLayoutParams($build, $string, $font, $width, $align);
        return $params;
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

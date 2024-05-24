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
use UI\Control\OpenTypeFeatures;

/**
 * @method int getType()
 * @method string family()
 * @method float size()
 * @method int weight()
 * @method int italic()
 * @method int stretch()
 * @method int underline()
 * @property-read int $type     specify of \UI\UI::ATTRIBUTE_TYPE_*
 * @property-read string $font
 * @property-read float $red
 * @property-read float $green
 * @property-read float $blue
 * @property-read float $alpha
 * @property-read \UI\Control\OpenTypeFeatures $control
 * @property-read int $italic   specify of \UI\UI::TEXT_ITALIC_*
 * @property-read float $size
 * @property-read int $stretch  specify of \UI\UI::TEXT_STRETCH_*
 * @property-read int $underline specify of \UI\UI::UNDERLINE_*
 * @property-read int $weight   specify of \UI\UI::TEXT_WEIGHT_*
 * @property-read int $underlineColor  specify of UI\UI::UNDERLINE_COLOR_*
 * 
 */
class Attribute extends Control
{
    const CTL_NAME = 'attribute';

    protected $callPrefix = 'attribute';
    protected $callPrefixFuncList = ['getType', 'family', 'size', 'weight', 'italic', 'stretch', 'underline'];

    protected function newControl(): CData
    {
        switch ($this->attr['type']) {
            case self::$ui::ATTRIBUTE_TYPE_FAMILY:
                $this->instance = self::$ui->newFamilyAttribute($this->attr['font']);
                break;
            case self::$ui::ATTRIBUTE_TYPE_BACKGROUND:
                $this->instance = self::$ui->newBackgroundAttribute($this->attr['red'], $this->attr['green'], $this->attr['blue'], $this->attr['alpha']);
                break;
            case self::$ui::ATTRIBUTE_TYPE_COLOR:
                $this->instance = self::$ui->newColorAttribute($this->attr['red'], $this->attr['green'], $this->attr['blue'], $this->attr['alpha']);
                break;
            case self::$ui::ATTRIBUTE_TYPE_FEATURES:
                $this->instance = self::$ui->newFeaturesAttribute($this->attr['control']->getUIInstance());
                break;
            case self::$ui::ATTRIBUTE_TYPE_ITALIC:
                $this->instance = self::$ui->newItalicAttribute($this->attr['italic']);
                break;
            case self::$ui::ATTRIBUTE_TYPE_SIZE:
                $this->instance = self::$ui->newSizeAttribute($this->attr['size']);
                break;
            case self::$ui::ATTRIBUTE_TYPE_STRETCH:
                $this->instance = self::$ui->newStretchAttribute($this->attr['stretch']);
                break;
            case self::$ui::ATTRIBUTE_TYPE_UNDERLINE:
                $this->instance = self::$ui->newUnderlineAttribute($this->attr['underline']);
                break;
            case self::$ui::ATTRIBUTE_TYPE_UNDERLINE_COLOR:
                $this->instance = self::$ui->newUnderlineColorAttribute($this->attr['underlineColor'], $this->attr['red'], $this->attr['green'], $this->attr['blue'], $this->attr['alpha']);
                break;
            case self::$ui::ATTRIBUTE_TYPE_WEIGHT:
                $this->instance = self::$ui->newWeightAttribute($this->attr['weight']);
                break;
        }
        return $this->instance;
    }

    public function color(&$r, &$g, &$b, &$a)
    {
        $rptr = self::$ui->new('double*');
        $gptr = self::$ui->new('double*');
        $bptr = self::$ui->new('double*');
        $aptr = self::$ui->new('double*');
        $this->attributeColor($rptr, $gptr, $bptr, $aptr);
        $r = $rptr[0];
        $g = $gptr[0];
        $b = $bptr[0];
        $a = $aptr[0];
    }

    public function underlineColor(&$u, &$r, &$g, &$b, &$a)
    {
        $uptr = self::$ui->new('uiUnderlineColor*');
        $rptr = self::$ui->new('double*');
        $gptr = self::$ui->new('double*');
        $bptr = self::$ui->new('double*');
        $aptr = self::$ui->new('double*');
        $this->attributeUnderlineColor($uptr, $rptr, $gptr, $bptr, $aptr);
        $u = $uptr[0];
        $r = $rptr[0];
        $g = $gptr[0];
        $b = $bptr[0];
        $a = $aptr[0];
    }

    public function features()
    {
        if ($this->attr['type'] === self::$ui::ATTRIBUTE_TYPE_FEATURES) {
            $control = self::$ui->attributeFeatures();
            $handle = self::$ui->controlHandle($control);
            $open = $this->build->getControlByHandle($handle);
            if ($open) {
                return $open;
            }
            return new OpenTypeFeatures($this->build, [], $control);
        }
    }

}

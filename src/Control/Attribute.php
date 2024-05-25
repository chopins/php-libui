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
use TypeError;
use UI\Control\OpenTypeFeatures;
use UI\Struct\AttributeType;
use UI\Struct\TextItalic;
use UI\Struct\TextStretch;
use UI\Struct\TextWeight;
use UI\Struct\Underline;
use UI\Struct\UnderlineColor;

/**
 * @method AttributeType getType():
 * @method string family()
 * @method float size()
 * @method TextWeight weight()
 * @method TextItalic italic()
 * @method TextStretch stretch()
 * @method Underline underline()
 * @property-read AttributeType $type     specify of \UI\UI::ATTRIBUTE_TYPE_*
 * @property-read string $font
 * @property-read float $red
 * @property-read float $green
 * @property-read float $blue
 * @property-read float $alpha
 * @property-read \UI\Control\OpenTypeFeatures $control
 * @property-read TextItalic $italic
 * @property-read float $size
 * @property-read TextStretch $stretch
 * @property-read Underline $underline 
 * @property-read TextWeight $weight
 * @property-read UnderlineColor $underlineColor
 * 
 */
class Attribute extends Control
{
    const CTL_NAME = 'attribute';
    const IS_CONTROL = false;

    protected $callPrefix = 'attribute';
    protected $callPrefixFuncList = ['getType', 'family', 'size', 'weight', 'italic', 'stretch', 'underline'];

    protected function newControl(): CData
    {
        switch ($this->attr['type']) {
            case AttributeType::ATTRIBUTE_TYPE_FAMILY:
                $this->instance = self::$ui->newFamilyAttribute($this->attr['font']);
                break;
            case AttributeType::ATTRIBUTE_TYPE_BACKGROUND:
                $this->instance = self::$ui->newBackgroundAttribute($this->attr['red'], $this->attr['green'], $this->attr['blue'], $this->attr['alpha']);
                break;
            case AttributeType::ATTRIBUTE_TYPE_COLOR:
                $this->instance = self::$ui->newColorAttribute($this->attr['red'], $this->attr['green'], $this->attr['blue'], $this->attr['alpha']);
                break;
            case AttributeType::ATTRIBUTE_TYPE_FEATURES:
                $this->instance = self::$ui->newFeaturesAttribute($this->attr['control']->getUIInstance());
                break;
            case AttributeType::ATTRIBUTE_TYPE_ITALIC:
                $this->assertEnum($this->attr['italic'], TextItalic::class);
                $this->instance = self::$ui->newItalicAttribute($this->attr['italic']->value);
                break;
            case AttributeType::ATTRIBUTE_TYPE_SIZE:
                $this->instance = self::$ui->newSizeAttribute($this->attr['size']);
                break;
            case AttributeType::ATTRIBUTE_TYPE_STRETCH:
                $this->assertEnum($this->attr['stretch'], TextStretch::class);
                $this->instance = self::$ui->newStretchAttribute($this->attr['stretch']->value);
                break;
            case AttributeType::ATTRIBUTE_TYPE_UNDERLINE:
                $this->assertEnum($this->attr['underline'], Underline::class);
                $this->instance = self::$ui->newUnderlineAttribute($this->attr['underline']->value);
                break;
            case AttributeType::ATTRIBUTE_TYPE_UNDERLINE_COLOR:
                $this->assertEnum($this->attr['underlineColor'], UnderlineColor::class);
                $this->instance = self::$ui->newUnderlineColorAttribute($this->attr['underlineColor']->value, $this->attr['red'], $this->attr['green'], $this->attr['blue'], $this->attr['alpha']);
                break;
            case AttributeType::ATTRIBUTE_TYPE_WEIGHT:
                $this->assertEnum($this->attr['weight'], TextWeight::class);
                $this->instance = self::$ui->newWeightAttribute($this->attr['weight']->value);
                break;
            default:
                throw new TypeError('Unknow AttributeType');
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
        if ($this->attr['type'] === AttributeType::ATTRIBUTE_TYPE_FEATURES) {
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

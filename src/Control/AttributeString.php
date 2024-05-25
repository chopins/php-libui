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
use UI\Control\Attribute;
use UI\Struct\ForEachStatus;
use UI\Struct\AttributeType;

/**
 * @method string string()
 * @method int len()
 * @method void appendUnattributed(string $str)
 * @method void insertAtUnattributed(string $str, int $at)
 * @method void delete(int $start, int $end)
 * @method int numGraphemes()
 * @method int byteIndexToGrapheme(int $pos)
 * @method int graphemeToByteIndex(int $post)
 */
class AttributeString extends Control
{
    const CTL_NAME = 'string';
    const IS_CONTROL = false;
    const TYPE_MAP = [
        'color' =>  AttributeType::ATTRIBUTE_TYPE_COLOR,
        'bgcolor' => AttributeType::ATTRIBUTE_TYPE_BACKGROUND,
        'font' => AttributeType::ATTRIBUTE_TYPE_FAMILY,
        'control' => AttributeType::ATTRIBUTE_TYPE_FEATURES,
        'italic' => AttributeType::ATTRIBUTE_TYPE_ITALIC,
        'size' => AttributeType::ATTRIBUTE_TYPE_SIZE,
        'stretch' => AttributeType::ATTRIBUTE_TYPE_STRETCH,
        'underline' => AttributeType::ATTRIBUTE_TYPE_UNDERLINE,
        'underlineColor' => AttributeType::ATTRIBUTE_TYPE_UNDERLINE_COLOR,
        'weight' => AttributeType::ATTRIBUTE_TYPE_WEIGHT
    ];

    protected $callPrefix = 'attributedString';
    protected $callPrefixFuncList = [
        'string', 'len', 'appendUnattributed', 'insertAtUnattributed',
        'delete', 'numGraphemes', 'byteIndexToGrapheme', 'graphemeToByteIndex'
    ];

    protected function newControl(): CData
    {
        $this->instance = self::$ui->newAttributedString($this->attr['string']);
        $this->addAllAttr();
        return $this->instance;
    }

    public function free()
    {
        $this->freeAttributedString();
    }

    public function len()
    {
        return strlen($this->attr['string']);
    }

    protected function addAllAttr()
    {
        foreach (self::TYPE_MAP as $k => $t) {

            if (isset($this->attr[$k])) {
                $a = [$k => $t];
                if ($k == 'color' || $k == 'bgcolor' || $k == 'underlineColor') {
                    if (is_string($this->attr[$k])) {
                        list($a['red'], $a['green'], $a['blue'], $a['alpha']) = explode(',', $this->attr[$k]);
                        $a['red'] = floatval($a['red']);
                        $a['green'] = floatval($a['green']);
                        $a['blue'] = floatval($a['blue']);
                        $a['alpha'] = floatval($a['alpha']);
                    } elseif (is_array($this->attr[$k])) {
                        $a = array_merge($a, $this->attr[$k]);
                    }
                }
                $attType = new Attribute($this->build, ['type' => $t, ...$a]);
                $this->setAttribute($attType, 0, $this->len());
            }
        }
    }
    public function addAttr(string $k, $value)
    {
        $a = [$k => self::TYPE_MAP[$k]];
        if ($k == 'color' || $k == 'bgcolor' || $k == 'underlineColor') {
            list($a['red'], $a['green'], $a['blue'], $a['alpha']) = explode(',', $value);
        }
        $attType = new Attribute($this->build, ['type' => self::TYPE_MAP[$k], ...$a]);
        $this->setAttribute($attType, 0, $this->len());
    }

    public function setAttribute(Attribute $a, int $start, int $end)
    {
        $this->attributedStringSetAttribute($a->getUIInstance(), $start, $end);
    }

    public function forEachAttribute($callable, $data = null)
    {
        $func = function ($s, $a, $start, $end, $cdata) use ($callable, $data): ForEachStatus {
            $handle = self::$ui->controlHandle($a);
            $ac = $this->build->getControlByHandle($handle);
            $attr = $ac ?? new Attribute($this->build, [], $a);
            return $callable($this, $attr, $start, $end, $data);
        };
        $this->attributedStringForEachAttribute($func, null);
    }
}

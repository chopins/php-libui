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

    protected $callPrefix = 'attributedString';
    protected $callPrefixFuncList = ['string', 'len', 'appendUnattributed', 'insertAtUnattributed',
        'delete', 'numGraphemes', 'byteIndexToGrapheme', 'graphemeToByteIndex'];

    public function newControl(): CData
    {
        $this->instance = self::$ui->newAttributedString($this->attr['string']);
        return $this->instance;
    }

    public function free()
    {
        $this->freeAttributedString();
    }

    public function setAttribute(Attribute $a, int $start, int $end)
    {
        $this->attributedStringSetAttribute($a->getUIInstance(), $start, $end);
    }

    public function forEachAttribute($callable, $data = null)
    {
        $func = function ($s, $a, $start, $end, $passdata) use ($callable, $data) {
            $handle = self::$ui->controlHandle($a);
            $ac = $this->build->getControlByHandle($handle);
            $attr = $ac ?? new Attribute($this->build, [], $a);
            return (int) $callable($this, $attr, $start, $end, $data);
        };
        $this->attributedStringForEachAttribute($func, null);
    }

}

<?php

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

    public function __call($func, $args = [])
    {
        $func = 'attributedString' . ucfirst($func);
        return parent::__call($func, $args);
    }

}

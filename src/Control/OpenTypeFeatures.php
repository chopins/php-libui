<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

/**
 * @method void add(char a, char b, char c, char d, uint32_t value)
 * @method void remove(char a, char b, char c, char d)
 */
class OpenTypeFeatures extends Control
{
    const CTL_NAME = 'open_type';
    public function newControl(): CData
    {
        $this->instance = self::$ui->newOpenTypeFeatures();
        return $this->instance;
    }

    public function clone()
    {
        $control = $this->openTypeFeaturesClone();
        return new static($this->build, [], $control);
    }

    public function free()
    {
        $this->freeOpenTypeFeatures();
    }

    public function get($a, $b, $c, $d, &$v): int
    {
        $value = self::$ui->new('uint32_t *');
        $ret = $this->openTypeFeaturesGet($a, $b, $c, $d, $value);
        $v = $value[0];
        return $ret;
    }

    public function __call($func, $args = [])
    {
        $func = 'openTypeFeatures' . ucfirst($func);
        return parent::__call($func, $args);
    }

    public function forEach(callable $callalbe, $data = null)
    {
        $func = function ($otf, $a, $b, $c, $d, $value, $passdata) use ($callalbe, $data) {
            $ret = $callalbe($this, $a, $b, $c, $d, $value, $data);
            return \intval($ret);
        };
        $this->openTypeFeaturesForEach($func, null);
    }
}

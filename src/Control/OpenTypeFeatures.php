<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

namespace UI\Control;

use UI\Control;
use FFI\CData;
use UI\Struct\ForEachStatus;

/**
 * @method void add(char a, char b, char c, char d, uint32_t value)
 * @method void remove(char a, char b, char c, char d)
 */
class OpenTypeFeatures extends Control
{
    const CTL_NAME = 'feature';
    const IS_CONTROL = false;

    protected $callPrefix = 'openTypeFeatures';
    protected $callPrefixFuncList = ['add', 'remove'];

    protected function newControl(): CData
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

    public function forEach(callable $callalbe, $data = null)
    {
        $func = function ($otf, $a, $b, $c, $d, $value, $cdata) use ($callalbe, $data): ForEachStatus {
            return $callalbe($this, $a, $b, $c, $d, $value, $data);
        };
        $this->openTypeFeaturesForEach($func, null);
    }
}

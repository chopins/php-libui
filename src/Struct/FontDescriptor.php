<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 * @version    0.1
 */

namespace UI\Struct;

use UI\UIBuild;

class FontDescriptor
{
    protected $structInstance = null;
    private static $ui;

    public function __construct(UIBuild $build)
    {
        self::$ui = $build->getUI();
        $this->structInstance = self::$ui->new('uiFontDescriptor');
    }

    public function fill(string $family, float $size, TextWeight $weight, TextItalic $italic, TextStretch $stretch)
    {
        $this->structInstance->Family = $family;
        $this->structInstance->Size = $size;
        $this->structInstance->Weight =  $weight->value;
        $this->structInstance->Italic = $italic->value;
        $this->structInstance->Stretch = $stretch->value;
    }

    public function __get($name)
    {
        $name = ucfirst(strtolower($name));
        if($name == 'Family' || $name == 'Size') {
            return $this->structInstance->$name;
        }
        switch($name) {
            case 'Weight':
                return TextWeight::tryFrom($this->structInstance->$name);
            case 'Italic':
                return TextItalic::tryFrom($this->structInstance->$name);
            case 'Stretch':
                return TextStretch::tryFrom($this->structInstance->$name);
        }
    }

    public function value($ptr = true)
    {
        return $ptr ? self::$ui->addr($this->structInstance) : $this->structInstance;
    }

    public function free()
    {
        self::$ui->freeFontButtonFont(self::$ui->addr($this->structInstance));
    }

    public function __destruct()
    {
        $this->free();
    }
}

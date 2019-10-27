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
    protected string $family;
    protected float $size;
    protected int $weight;
    protected int $italic;
    protected int $stretch;
    protected $structInstance = null;
    private static $ui;

    public function __construct(UIBuild $build)
    {
        self::$ui = $build->getUI();
        $this->structInstance = self::$ui->new('uiFontDescriptor');
    }

    public function fill()
    {
        $this->family = self::$ui->string($this->structInstance->Family);
        $this->size = $this->structInstance->Size;
        $this->weight = $this->structInstance->Weight;
        $this->italic = $this->structInstance->Italic;
        $this->stretch = $this->structInstance->Stretch;
    }

    public function getFontDescriptor($ptr = true)
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

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

use UI\Struct\FontDescriptor;
use UI\Control\AttributeString;
use UI\UIBuild;

class TextLayoutParams
{
    public AttributeString $string;
    public FontDescriptor $defaultFont;
    public float $width;
    public int $align;
    private static $ui;
    protected $structInstance = null;

    public function __construct(UIBuild $build)
    {
        self::$ui = $build->getUI();
        $this->structInstance = self::$ui->new('uiDrawTextLayoutParams');
    }

    public function getParams($ptr = true)
    {
        $this->structInstance->String = $this->string->getUIInstance();
        $this->structInstance->DefaultFont = $this->defaultFont->getFontDescriptor();
        $this->structInstance->Width = $this->width;
        $this->structInstance->Align = $this->align;
        return $ptr ? self::$ui->addr($this->structInstance) : $this->structInstance;
    }

}

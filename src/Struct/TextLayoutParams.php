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
    private static $ui;
    protected $structInstance = null;
    protected $ptr = null;

    public function __construct(UIBuild $build, AttributeString $string, FontDescriptor $defaultFont, float $width, DrawTextAlign $align)
    {
        self::$ui = $build->getUI();
        $this->structInstance = self::$ui->new('uiDrawTextLayoutParams');
        $this->structInstance->String = $string->getUIInstance();
        $this->structInstance->DefaultFont = $defaultFont->value();
        $this->structInstance->Width = $width;
        $this->structInstance->Align = $align->value;
        $this->ptr = self::$ui->addr($this->structInstance);
    }
    public function value($ptr = true) 
    {
        if($ptr) {
            return $this->ptr;
        }
        return $this->structInstance;
    }
}

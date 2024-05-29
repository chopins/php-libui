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

use FFI;
use UI\Struct\FontDescriptor;
use UI\Control\AttributeString;
use UI\UIBuild;

class TextLayoutParams
{
    private static $ui;
    protected $structInstance = null;
    protected $ptr = null;
    protected AttributeString $string;
    protected FontDescriptor $font;

    public function __construct(UIBuild $build, AttributeString $string, FontDescriptor $defaultFont, float $width, DrawTextAlign $align)
    {
        self::$ui = $build->getUI();
        $this->string = $string;
        $this->font = $defaultFont;
        $this->structInstance = self::$ui->new('uiDrawTextLayoutParams');
        $this->structInstance->String = $string->getUIInstance();
        $this->structInstance->DefaultFont = $defaultFont->value();
        $this->structInstance->Width = $width;
        $this->structInstance->Align = $align->value;
        $this->ptr = FFI::addr($this->structInstance);
    }
    public function string()
    {
        return $this->string;
    }
    public function value($ptr = true)
    {
        if ($ptr) {
            return $this->ptr;
        }
        return $this->structInstance;
    }

    public function __destruct()
    {
        unset($this->string);
        unset($this->font);
    }
}

<?php
namespace UI\Struct;

use UI\Control\AttributeString;
use UI\Struct\FontDescriptor;
use UI\UIBuild;

class TextLayoutParams {
    public AttributedString $string;
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

    public function getParams($ptr = true) {
        $this->structInstance->String = $this->string->getUIInstance();
        $this->structInstance->DefaultFont = $this->defaultFont->getFontDescriptor();
        $this->structInstance->Width = $this->width;
        $this->structInstance->Align = $this->align;
        return $ptr ? self::$ui->addr($this->structInstance) : $this->structInstance;
    }

}
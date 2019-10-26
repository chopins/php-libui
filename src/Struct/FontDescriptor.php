<?php

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
        self::$ui =  $build->getUI();
        $this->structInstance = self::$ui->new('uiFontDescriptor*');
    }

    public function fill()
    {
        $this->family = self::$ui::string($this->structInstance[0]->Family);
        $this->size = $this->structInstance[0]->Size;
        $this->weight = $this->structInstance[0]->Weight;
        $this->italic = $this->structInstance[0]->Italic;
        $this->stretch = $this->structInstance[0]->Stretch;
    }

    public function getFontDescriptor()
    {
        return $this->structInstance;
    }

    public function free()
    {
        self::$ui->freeFontButtonFont($this->structInstance);
    }

    public function __destruct()
    {
        $this->free();
    }
 }

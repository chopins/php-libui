<?php

namespace UI\Control\Draw;

use UI\UIBuild;

class StrokeParams
{
    public int $cap;
    public int $join;
    public float $thickness;
    public float $miterLimit;
    public float $dashes;
    public int $numDashes;
    public float $dashPhase;

    protected static $ui;
    public function __construct(UIBuild $build)
    {
        self::$ui = $build->getUI();
    }
    public function getStrokeParams($ptr = true)
    {
        $paramType = self::$ui->new('uiDrawStrokeParams');
        $paramType->Cap = $this->cap;
        $paramType->Join = $this->join;
        $paramType->Thickness = $this->thickness;
        $paramType->MiterLimit = $this->MiterLimit;
        $dashes = self::$ui->new('double');
        $dashes->cdata = $this->dashes;
        $paramType->Dashes = self::$ui->addr($dashes);
        $paramType->NumDashes = $this->NumDashes;
        $paramType->DashPhase = $this->DashPhase;
        return $ptr ? self::$ui->addr($paramType) : $paramType;
    }
}

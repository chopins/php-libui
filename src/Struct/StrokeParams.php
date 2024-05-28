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
use UI\UIBuild;

class StrokeParams
{
    public DrawLineCap $cap;
    public DrawLineJoin $join;
    public float $thickness;
    public float $miterLimit;
    public float $dashes;
    public int $numDashes;
    public float $dashPhase;
    protected static UI\UI $ui;

    public function __construct(UIBuild $build)
    {
        self::$ui = $build->getUI();
    }

    public function getStrokeParams($ptr = true)
    {
        $paramType = self::$ui->new('uiDrawStrokeParams');
        $paramType->Cap = $this->cap->value;
        $paramType->Join = $this->join->value;
        $paramType->Thickness = $this->thickness;
        $paramType->MiterLimit = $this->miterLimit;
        $dashes = self::$ui->new('double');
        $dashes->cdata = $this->dashes;
        $paramType->Dashes = FFI::addr($dashes);
        $paramType->NumDashes = $this->numDashes;
        $paramType->DashPhase = $this->dashPhase;
        return $ptr ? FFI::addr($paramType) : $paramType;
    }

}

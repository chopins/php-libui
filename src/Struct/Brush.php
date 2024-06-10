<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 *
 */

namespace UI\Struct;

use FFI;
use UI\UI;
use UI\UIBuild;

class Brush
{
    public DrawBrushType $type;
    public float $R;
    public float $G;
    public float $B;
    public float $A;
    public float $X0;
    public float $Y0;
    public float $X1;
    public float $Y1;
    public float $outerRadius;
    public float $stopsPos;
    public float $stopsR;
    public float $stopsG;
    public float $stopsB;
    public float $stopsA;
    public int $numStops;
    protected static UI\UI $ui;
    protected FFI\CData $structInstance;
    protected FFI\CData $stops;

    public function __construct(UIBuild $build)
    {
        self::$ui = $build->getUI();
        $this->structInstance = self::$ui->new('uiDrawBrush');
        $this->stops = self::$ui->new('uiDrawBrushGradientStop');
    }

    public function getBrush($ptr = true)
    {
        $this->stops->Pos = $this->stopsPos;
        $this->stops->R = $this->stopsR;
        $this->stops->G = $this->stopsG;
        $this->stops->B = $this->stopsB;
        $this->stops->A = $this->stopsA;

        $this->structInstance->Type = $this->type->value;
        $this->structInstance->R = $this->R;
        $this->structInstance->G = $this->G;
        $this->structInstance->B = $this->B;
        $this->structInstance->A = $this->A;
        $this->structInstance->X0 = $this->X0;
        $this->structInstance->Y0 = $this->Y0;
        $this->structInstance->X1 = $this->X1;
        $this->structInstance->Y1 = $this->Y1;
        $this->structInstance->OuterRadius = $this->outerRadius;
        $this->structInstance->Stops = FFI::addr($this->stops);
        $this->structInstance->NumStops = $this->numStops;
        return $ptr ? FFI::addr($this->structInstance) : $this->structInstance;
    }

}

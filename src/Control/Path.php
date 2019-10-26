<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;
use UI\Struct\Brush;
use UI\Struct\StrokeParams;

/**
 * @method void newFigure(float x, float y)
 * @method void newFigureWithArc(float xCenter, float yCenter, float radius, float startAngle, float sweep, int negative)
 * @method void lineTo(float $x, float $y)
 * @method void arcTo(float $xCenter, float $yCenter, float $radius, float $startAngle, float $sweep, int $negative)
 * @method void bezierTo(float $c1x, float $c1y, float $c2x, float $c2y, float $endX, float $endY)
 * @method void closeFigure()
 * @method void addRectangle(float $x, float $y, float $width, float $height)
 * @method void end()
 * @property-read int $fillMode
 *
 */
class Path extends Control
{
    const CTL_NAME = 'path';
    public function newControl(): CData
    {
        $this->instance = self::$ui->drawNewPath($this->attr['fillMode']);
        return $this->instance;
    }

    public function free()
    {
        $this->drawFreePath();
    }

    public function __call($func, $args)
    {
        $func = 'drawPath' . ucfirst($func);
        return parent::__call($func, $args);
    }

    public function newBrush() {
        return new Brush($this->build);
    }

    public function newStrokeParams() {
        return new StrokeParams($this->build);
    }
}

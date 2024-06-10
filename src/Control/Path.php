<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

namespace UI\Control;

use UI\Control;
use FFI\CData;
use UI\Struct\Brush;
use UI\Struct\DrawFillMode;
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
 * @property-read int $fillMode  specify of value \UI\UI::DRAW_FILL_MODE_*
 *
 */
class Path extends Control
{
    const CTL_NAME = 'path';
    const IS_CONTROL = false;

    protected $callPrefix = 'drawPath';
    protected $callPrefixFuncList = [
        'newFigure', 'newFigureWithArc', 'lineTo', 'arcTo', 'bezierTo',
        'closeFigure', 'addRectangle', 'end'
    ];

    protected function newControl(): CData
    {
        $this->assertEnum($this->attr['fillMode'], DrawFillMode::class);
        $this->instance = self::$ui->drawNewPath($this->attr['fillMode']->value);
        return $this->instance;
    }

    public function free()
    {
        $this->drawFreePath();
    }

    public function newBrush()
    {
        return new Brush($this->build);
    }

    public function newStrokeParams()
    {
        return new StrokeParams($this->build);
    }
}

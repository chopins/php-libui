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
use UI\UIBuild;

/**
 * @method void translate(float $x, float $y)
 * @method void scale(float $xCenter, float $yCenter, float $x, float $y)
 * @method void rotate(float $x, float $y, float $amount)
 * @method void skew(float $x, float $y, float $xamount, float $yamount)
 * @method int invertible()
 * @method int invert()
 */
class Matrix
{
    protected static $ui;
    public float $M11;
    public float $M12;
    public float $M21;
    public float $M22;
    public float $M31;
    public float $M32;
    protected $structInstance = null;
    protected $build = null;

    public function __construct(UIBuild $build)
    {
        $this->build = $build;
        self::$ui = $build->getUI();
        $this->setIdentity();
    }

    public function multiply()
    {
        $dstPtr = self::$ui->new('uiDrawMatrix*');
        self::$ui->drawMatrixMultiply($dstPtr, $this->getMatrix());
        $dest = new static($this->build);
        $dest->structInstance = $dstPtr;
        return $dest;
    }

    public function getMatrix($ptr = true)
    {
        return $ptr ? FFI::addr($this->structInstance) : $this->structInstance;
    }

    public function setIdentity()
    {
        $this->structInstance = self::$ui->new('uiDrawMatrix');
        self::$ui->drawMatrixSetIdentity($this->getMatrix());
    }

    public function transformPoint(&$x, &$y)
    {
        $xfptr = self::$ui->new('float *');
        $yfptr = self::$ui->new('float *');
        self::$ui->drawMatrixTransformPoint($this->getMatrix(), $xfptr, $yfptr);
        $x = $xfptr[0];
        $y = $yfptr[0];
    }

    public function transformSize(&$x, &$y)
    {
        $xfptr = self::$ui->new('float *');
        $yfptr = self::$ui->new('float *');
        self::$ui->drawMatrixTransformSize($this->getMatrix(), $xfptr, $yfptr);
        $x = $xfptr[0];
        $y = $yfptr[0];
    }

    public function __call($name, $arguments)
    {
        $func = 'drawMatrix' . ucfirst($name);
        array_unshift($arguments, $this->getMatrix());
        return call_user_func_array([self::$ui, $func], $arguments);
    }

}

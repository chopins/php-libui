<?php

namespace UI\Control\Draw;

use UI\UIBuild;

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

    public function __construct(UIBuild $build)
    {
        self::$ui = $build->getUI();
        $this->setIdentity();
    }

    public function setIdentity()
    {
        $this->structInstance = self::$ui->new('uiDrawMatrix');
        self::$ui->drawMatrixSetIdentity(self::$ui->addr($this->structInstance));
    }

    public function __call($name, $arguments)
    {
        $func = 'drawMatrix' . ucfirst($name);
        array_unshift($arguments, $this->structInstance);
        return call_user_func_array([self::$ui, $func], $arguments);
    }
}

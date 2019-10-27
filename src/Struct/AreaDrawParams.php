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

use UI\UIBuild;

class AreaDrawParams
{
    public $context = null;
    public float $areaWidth = 0.0;
    public float $areaHeight = 0.0;
    public float $clipX = 0.0;
    public float $clipY = 0.0;
    public float $clipWidth = 0.0;
    public float $clipHeight = 0.0;
    protected static $ui;
    protected $structInstance = null;
    protected $build = null;

    public function __construct(UIBuild $build, $params = null)
    {
        $this->build = $build;
        self::$ui = $build->getUI();

        if ($params !== null) {
            $this->structInstance = $params;
            $this->fill();
        } else {
            $this->structInstance = self::$ui->new('uiAreaDrawParams');
        }
    }

    public function fill()
    {
        $this->context = $this->structInstance->Context;
        $this->areaWidth = $this->structInstance->AreaWidth;
        $this->areaHeight = $this->structInstance->AreaHeight;
        $this->clipX = $this->structInstance->ClipX;
        $this->clipY = $this->structInstance->ClipY;
        $this->clipWidth = $this->structInstance->ClipWidth;
        $this->clipHeight = $this->structInstance->ClipHeight;
    }

    public function getParams($ptr = true)
    {
        return $ptr ? self::$ui->addr($this->structInstance) : $this->structInstance;
    }

}

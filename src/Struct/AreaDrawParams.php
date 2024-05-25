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
        } else {
            $this->structInstance = self::$ui->new('uiAreaDrawParams');
        }
    }

    public function fill(FFI\CData $context, float $areaWidth, float $areaHeight, float $clipX, float $clipY, float $clipWidth, float $clipHeight)
    {
        $this->structInstance->Context = $context;
        $this->structInstance->AreaWidth = $areaWidth;
        $this->structInstance->AreaHeight = $areaHeight;
        $this->structInstance->ClipX = $clipX;
        $this->structInstance->ClipY = $clipY;
        $this->structInstance->ClipWidth = $clipWidth;
        $this->structInstance->ClipHeight = $clipHeight;
    }

    public function value($ptr = true)
    {
        return $ptr ? self::$ui->addr($this->structInstance) : $this->structInstance;
    }
}

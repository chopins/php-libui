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

/**
 * @property-read $context;
 * @property-read float $areaWidth;
 * @property-read float $areaHeight;
 * @property-read float $clipX;
 * @property-read float $clipY;
 * @property-read float $clipWidth;
 * @property-read float $clipHeight;
 */
class AreaDrawParams
{
    protected $structInstance = null;
    protected $build = null;
    protected static $ui;

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
    public function __get($name)
    {
        $name = ucfirst($name);
        return  $this->structInstance->$name;
    }

    public function value($ptr = true)
    {
        return $ptr ? FFI::addr($this->structInstance) : $this->structInstance;
    }
}

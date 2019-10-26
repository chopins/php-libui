<?php
/**
 * libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2011 - 2019 Toknot.com
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

 namespace UI\Struct;

class Struct
{
    const STRUCT_MAP = [
        'uiAreaHandler' => [
            'Draw' => ['callable', 'void', 'uiAreaHandler', 'uiControl', 'uiAreaDrawParams'],
            'MouseEvent' => ['callable', 'void', 'uiAreaHandler', 'uiControl', 'uiAreaMouseEvent'],
            'MouseCrossed' => ['callable', 'void', 'uiAreaHandler', 'uiControl', 'int'],
            'DragBroken' => ['callable', 'void', 'uiAreaHandler', 'uiControl'],
            'KeyEvent' => ['callable', 'int', 'uiAreaHandler', 'uiControl', 'uiAreaKeyEvent']
        ],
        'uiAreaDrawParams' => [
            'Context' => 'uiControl',
            'AreaWidth' => 'double',
            'AreaHeight' => 'double',
            'ClipX' => 'double',
            'ClipY' => 'double',
            'ClipWidth' => 'double',
            'ClipHeight' => 'double',
        ],
        'uiDrawMatrix' => [
            'M11' => 'double',
            'M12' => 'double',
            'M21' => 'double',
            'M22' => 'double',
            'M31' => 'double',
            'M32' => 'double',
        ],
        'uiDrawBrush' => [
            'Type' => 'int',
            'R' => 'double',
            'G' => 'double',
            'B' => 'double',
            'A;' => 'double',
            'X0' => 'double',
            'Y0' => 'double',
            'X1' => 'double',
            'Y1' => 'double',
            'OuterRadius' => 'double',
            'Stops' => 'uiDrawBrushGradientStop',
            'NumStops' => 'int',
        ],
        'uiDrawBrushGradientStop' => [
            'Pos' => 'double',
            'R' => 'double',
            'G' => 'double',
            'B' => 'double',
            'A' => 'double',
        ],
        'uiDrawStrokeParams' => [
            'Cap' => 'int',
            'Join' => 'int',
            'Thickness' => 'double',
            'MiterLimit' => 'double',
            '*Dashes' => 'double',
            'NumDashes' => 'int',
            'DashPhase' => 'double',
        ],
        'uiFontDescriptor' => [
            'Family' => 'string',
            'Size' => 'double',
            'Weight' => 'int',
            'Italic' => 'int',
            'Stretch' => 'int',
        ],
        'uiDrawTextLayoutParams' => [
            'String' => 'uiControl*',
            'DefaultFont' => 'uiFontDescriptor *',
            'Width' => 'double',
            'Align' => 'int',
        ],
        'uiAreaMouseEvent' =>    [
            'X' => 'double',
            'Y' => 'double',
            'AreaWidth' => 'double',
            'AreaHeight' => 'double',
            'Down' => 'int',
            'Up' => 'int',
            'Count' => 'int',
            'Modifiers' => 'int',
            'Held1To64' => 'int',
        ],
        'uiAreaKeyEvent' => [

            'Key' => 'string',
            'ExtKey' => 'int',
            'Modifier' => 'int',
            'Modifiers' => 'int',
            'Up' => 'int ',
        ],
        'uiTableModelHandler' => [
            'NumColumns' => ['callable', 'int', 'uiTableModelHandler', 'uiControl'],
            'ColumnType' => ['callable', 'int', 'uiTableModelHandler', 'uiControl', 'int'],
            'NumRows' => ['callable', 'int', 'uiTableModelHandler', 'uiControl'],
            'CellValue' => ['callable', 'uiControl', 'uiTableModelHandler', 'uiControl', 'int', 'int'],
            'SetCellValue' => ['callable', 'uiTableModelHandler', 'uiControl', 'int', 'int', 'const uiControl'],
        ],
        'uiTableTextColumnOptionalParams' => [
            'ColorModelColumn' => 'int',
        ],
        'uiTableParams' => [
            'Model' => 'uiControl',
            'RowBackgroundColorModelColumn' => 'int',
        ],
    ];

    private $ffi = null;

    public function __construct(UI $ui)
    {
        $this->ffi = $ui->ffi();
    }

    public function getStruct($type)
    {
        return self::STRUCT_MAP[$type];
    }

    public function getTypeSize($type)
    {
        return FFI::sizeof($this->getType($type));
    }

    public function getType($type)
    {
        return $this->ffi->type($type);
    }

    public function typePtr($type)
    {
        $t = $this->ffi->new($type);
        return FFI::addr($t);
    }

    public function __get(string $type)
    {
        if (isset(self::STRUCT_MAP[$type])) {
            return $this->ffi->new($type);
        }
        throw new TypeError("C struct $type not defined");
    }

    public function __set(string $type, array $arr)
    {
        if (!isset(self::STRUCT_MAP[$type])) {
            throw new TypeError("C struct $type not defined");
        }
        $cdata = $this->ffi->new($type);
        foreach ($arr as $k => $v) {
            if (isset(self::STRUCT_MAP[$type][$k])) {
                $subType = self::STRUCT_MAP[$type][$k];
                if (is_array($subType) && $subType[0] === 'callable' && !is_callable($v)) {
                    throw new TypeError("C struct $type->$k must callable");
                } elseif ($subType === 'int' && !is_int($v)) {
                    throw new TypeError("C struct $type->$k must int");
                } elseif ($subType === 'double' && !is_float($v)) {
                    throw new TypeError("C struct $type->$k must float");
                } elseif ($subType === 'string' && !is_string($v)) {
                    throw new TypeError("C struct $type->$k must string");
                } elseif (!$v instanceof FFI\CData) {
                    throw new TypeError("C struct $type->$k must a c struct $subType handles");
                }
                $cdata->$k = $v;
            }
        }
    }
}

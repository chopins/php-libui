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

class FontDescriptor
{
    protected $structInstance = null;
    private static $ui;

    public function __construct(UIBuild $build)
    {
        self::$ui = $build->getUI();
        $this->structInstance = self::$ui->new('uiFontDescriptor');
    }

    public function fill(
        string $family,
        float $size = 13,
        TextWeight $weight = TextWeight::TEXT_WEIGHT_NORMAL,
        TextItalic $italic = TextItalic::TEXT_ITALIC_NORMAL,
        TextStretch $stretch = TextStretch::TEXT_STRETCH_NORMAL
    ) {

        $this->structInstance->Family = self::$ui->constChar($family);
        $this->structInstance->Size = $size;
        $this->structInstance->Weight =  $weight->value;
        $this->structInstance->Italic = $italic->value;
        $this->structInstance->Stretch = $stretch->value;
    }

    public function __get($name)
    {
        $name = ucfirst(strtolower($name));
        if ($name == 'Family' || $name == 'Size') {
            return $this->structInstance->$name;
        }
        switch ($name) {
            case 'Weight':
                return TextWeight::tryFrom($this->structInstance->$name);
            case 'Italic':
                return TextItalic::tryFrom($this->structInstance->$name);
            case 'Stretch':
                return TextStretch::tryFrom($this->structInstance->$name);
        }
    }

    protected function getAnyOneFonts($im, $family, $gmagick)
    {
        static $fcache = [];
        if (isset($fcache[$family])) {
            return $fcache[$family];
        }
        $fontList = $gmagick ? $im->queryFonts("*$family*") : $im::queryFonts("*$family*");
        if (empty($fontList)) {
            $fontList = $gmagick ? $im->queryFonts("*") : $im::queryFonts("*");
        }
        $fcache[$family] = $fontList;
        return $fontList;
    }

    public function queryFontMetrics()
    {
        static $mcache = [];
        $family = FFI::string($this->structInstance->Family);
        $size = $this->structInstance->Size;
        $cacheKey = "$family-$size";
        if (isset($mcache[$cacheKey])) {
            return $mcache[$cacheKey];
        }
        $gmagick = false;
        if (class_exists('\Gmagick')) {
            $imgClass = '\Gmagick';
            $imgDrawClass = '\GmagickDraw';
            $gmagick = true;
        } elseif (class_exists('\Imagick')) {
            $imgClass = '\Imagick';
            $imgDrawClass = '\ImagickDraw';
        } else {
            $mt = [
                'characterWidth' => $size,
                'characterHeight' => $size,
                'ascender' => $size,
                'descender' => -4,
                'textWidth' => 87,
                'textHeight' => $size + 4,
                'maxHorizontalAdvance' => ceil(($size + 4) / 2),
            ];
            $mcache[$cacheKey] = $mt;
            return $mt;
        }

        $im = new $imgClass();
        $draw = new $imgDrawClass();

        $family = strtr($family, ' ', '-');

        try {
            $draw->setFont($family);
        } catch (\Exception $e) {
            $fontList = $this->getAnyOneFonts($im, $family, $gmagick);
            if ($fontList) {
                $draw->setFont($fontList[0]);
            } else {
                $mt = [
                    'characterWidth' => $size,
                    'characterHeight' => $size,
                    'ascender' => $size,
                    'descender' => -4,
                    'textWidth' => 87,
                    'textHeight' => $size + 4,
                    'maxHorizontalAdvance' => ceil(($size + 4) / 2),
                ];
                $mcache[$cacheKey] = $mt;
                return $mt;
            }
        }
        $draw->setFontSize($size);
        $mt = $im->queryFontMetrics($draw, "QueryText查询字符");
        if ($gmagick) {
            $mt['maxHorizontalAdvance'] = $mt['maximumHorizontalAdvance'];
        }
        $mcache[$cacheKey] = $mt;
        return $mt;
    }

    public function value($ptr = true)
    {
        return $ptr ? FFI::addr($this->structInstance) : $this->structInstance;
    }

    public function free()
    {
        self::$ui->freeFontButtonFont(FFI::addr($this->structInstance));
    }

    public function __destruct()
    {
        $this->free();
    }
}

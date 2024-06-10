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

enum DrawBrushType: int
{
    case DRAW_BRUSH_TYPE_SOLID = 0;
    case DRAW_BRUSH_TYPE_LINEAR_GRADIENT = 1;
    case DRAW_BRUSH_TYPE_RADIAL_GRADIENT = 2;
    case DRAW_BRUSH_TYPE_IMAGE = 3;
}

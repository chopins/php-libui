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

enum DrawLineJoin: int
{
    case DRAW_LINE_JOIN_MITER = 0;
    case DRAW_LINE_JOIN_ROUND = 1;
    case DRAW_LINE_JOIN_BEVEL = 2;
}

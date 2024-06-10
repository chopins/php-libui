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

enum DrawLineCap: int
{
    case DRAW_LINE_CAP_FLAT = 0;
    case DRAW_LINE_CAP_ROUND = 1;
    case DRAW_LINE_CAP_SQUARE = 2;
}

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

enum DrawFillMode: int
{
    case DRAW_FILL_MODE_WINDING = 0;
    case DRAW_FILL_MODE_ALTERNATE = 1;
}

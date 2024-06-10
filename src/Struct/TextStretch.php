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

enum TextStretch: int
{
    case TEXT_STRETCH_ULTRA_CONDENSED = 0;
    case TEXT_STRETCH_EXTRA_CONDENSED = 1;
    case TEXT_STRETCH_CONDENSED = 2;
    case TEXT_STRETCH_SEMI_CONDENSED = 3;
    case TEXT_STRETCH_NORMAL = 4;
    case TEXT_STRETCH_SEMI_EXPANDED = 5;
    case TEXT_STRETCH_EXPANDED = 6;
    case TEXT_STRETCH_EXTRA_EXPANDED = 7;
    case TEXT_STRETCH_ULTRA_EXPANDED = 8;
}

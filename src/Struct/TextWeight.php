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

enum TextWeight : int
{
    case TEXT_WEIGHT_MINIMUM = 0;
    case TEXT_WEIGHT_THIN = 100;
    case TEXT_WEIGHT_ULTRA_LIGHT = 200;
    case TEXT_WEIGHT_LIGHT = 300;
    case TEXT_WEIGHT_BOOK = 350;
    case TEXT_WEIGHT_NORMAL = 400;
    case TEXT_WEIGHT_MEDIUM = 500;
    case TEXT_WEIGHT_SEMI_BOLD = 600;
    case TEXT_WEIGHT_BOLD = 700;
    case TEXT_WEIGHT_ULTRA_BOLD = 800;
    case TEXT_WEIGHT_HEAVY = 900;
    case TEXT_WEIGHT_ULTRA_HEAVY = 950;
    case TEXT_WEIGHT_MAXIMUM = 1000;
}

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

enum UIAt: int
{
    case AT_LEADING = 0;
    case AT_TOP = 1;
    case AT_TRAILING = 2;
    case AT_BOTTOM = 3;
}

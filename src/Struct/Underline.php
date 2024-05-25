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

enum Underline: int
{
    case UNDERLINE_NONE = 0;
    case UNDERLINE_SINGLE = 1;
    case UNDERLINE_DOUBLE = 2;
    case UNDERLINE_SUGGESTION = 3;
}

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

enum UnderlineColor: int
{
    case UNDERLINE_COLOR_CUSTOM = 0;
    case UNDERLINE_COLOR_SPELLING = 1;
    case UNDERLINE_COLOR_GRAMMAR = 2;
    case UNDERLINE_COLOR_AUXILIARY = 3;
}

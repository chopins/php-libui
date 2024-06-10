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

enum UIAlign: int
{
    case ALIGN_FILL = 0;
    case ALIGN_START = 1;
    case ALIGN_CENTER = 2;
    case ALIGN_END = 3;
}

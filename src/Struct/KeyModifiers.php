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

enum  KeyModifiers: int
{
    case MODIFIER_CTRL = 1 << 0;
    case MODIFIER_ALT = 1 << 1;
    case MODIFIER_SHIFT = 1 << 2;
    case MODIFIER_SUPER = 1 << 3;
}

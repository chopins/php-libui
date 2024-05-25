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

enum ExtKey: int
{
    case EXT_KEY_ESCAPE = 1;
    case EXT_KEY_INSERT = 2;
    case EXT_KEY_DELETE = 3;
    case EXT_KEY_HOME = 4;
    case EXT_KEY_END = 5;
    case EXT_KEY_PAGE_UP = 6;
    case EXT_KEY_PAGE_DOWN = 7;
    case EXT_KEY_UP = 8;
    case EXT_KEY_DOWN = 9;
    case EXT_KEY_LEFT = 10;
    case EXT_KEY_RIGHT = 11;
    case EXT_KEY_F1 = 12;
    case EXT_KEY_F2 = 13;
    case EXT_KEY_F3 = 14;
    case EXT_KEY_F4 = 15;
    case EXT_KEY_F5 = 16;
    case EXT_KEY_F6 = 17;
    case EXT_KEY_F7 = 18;
    case EXT_KEY_F8 = 19;
    case EXT_KEY_F9 = 20;
    case EXT_KEY_F10 = 21;
    case EXT_KEY_F11 = 22;
    case EXT_KEY_F12 = 23;
    case EXT_KEY_N0 = 24;
    case EXT_KEY_N1 = 25;
    case EXT_KEY_N2 = 26;
    case EXT_KEY_N3 = 27;
    case EXT_KEY_N4 = 28;
    case EXT_KEY_N5 = 29;
    case EXT_KEY_N6 = 30;
    case EXT_KEY_N7 = 31;
    case EXT_KEY_N8 = 32;
    case EXT_KEY_N9 = 33;
    case EXT_KEY_N_DOT = 34;
    case EXT_KEY_N_ENTER = 35;
    case EXT_KEY_N_ADD = 36;
    case EXT_KEY_N_SUBTRACT = 37;
    case EXT_KEY_N_MULTIPLY = 38;
    case EXT_KEY_N_DIVIDE = 39;
}

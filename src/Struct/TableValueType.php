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

enum TableValueType: int
{
    case TABLE_VALUE_TYPE_STRING = 0;
    case TABLE_VALUE_TYPE_IMAGE = 1;
    case TABLE_VALUE_TYPE_INT = 2;
    case TABLE_VALUE_TYPE_COLOR = 3;
}

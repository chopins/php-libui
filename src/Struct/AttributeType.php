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

enum AttributeType: int
{
    case ATTRIBUTE_TYPE_FAMILY = 0;
    case ATTRIBUTE_TYPE_SIZE = 1;
    case ATTRIBUTE_TYPE_WEIGHT = 2;
    case ATTRIBUTE_TYPE_ITALIC = 3;
    case ATTRIBUTE_TYPE_STRETCH = 4;
    case ATTRIBUTE_TYPE_COLOR = 4;
    case ATTRIBUTE_TYPE_BACKGROUND = 5;
    case ATTRIBUTE_TYPE_UNDERLINE = 6;
    case ATTRIBUTE_TYPE_UNDERLINE_COLOR = 7;
    case ATTRIBUTE_TYPE_FEATURES = 8;
}

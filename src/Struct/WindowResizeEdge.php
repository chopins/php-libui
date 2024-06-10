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

enum WindowResizeEdge: int
{
    case WINDOW_RESIZE_EDGE_LEFT = 0;
    case WINDOW_RESIZE_EDGE_TOP = 1;
    case WINDOW_RESIZE_EDGE_RIGHT = 2;
    case WINDOW_RESIZE_EDGE_BOTTOM = 3;
    case WINDOW_RESIZE_EDGE_TOP_LEFT = 4;
    case WINDOW_RESIZE_EDGE_TOP_RIGHT = 5;
    case WINDOW_RESIZE_EDGE_BOTTOM_LEFT = 6;
    case WINDOW_RESIZE_EDGE_BOTTOM_RIGHT = 7;
}

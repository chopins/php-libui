<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

/**
 * @property-read array $src
 * @property-read int $width
 * @property-read int $height
 */
class Img extends Control
{
    const CTL_NAME = 'img';
    public function newControl(): CData
    {
        if (!empty($this->attr['src'][0])) {
            $size = \getimagesize($this->attr['src'][0]);
            $this->attr['width'] = $this->attr['width'] ?? $size[0];
            $this->attr['height'] = $this->attr['height'] ?? $size[1];
        }
        $this->instance = self::$ui->newImage($this->attr['width'], $this->attr['height']);
        return $this->instance;
    }

    public function free()
    {
        $this->freeImage();
    }

    /**
     * @param string $imgData  The image data
     * @param int  $width
     * @param int  $height
     * @param int  $byteStride
     */
    public function append($imgData, int $width, int $height, int $byteStride)
    {
        $this->imageAppend($imgData,  $width,  $height,  $byteStride);
    }

    /**
     * @param string $img  The image file path
     */
    public function appendImg(string $img)
    {
        $this->attr['src'][] = $img;
        $size = \getimagesize($img);
        $data = \file_get_contents($img);
        $this->append($data, $size[0], $size[1], $size['bits']);
    }
}

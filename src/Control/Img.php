<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

class Img extends Control
{
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

    public function append($imgData, int $width, int $height, int $byteStride)
    {
        $this->imageAppend($imgData,  $width,  $height,  $byteStride);
    }

    public function appendImg(string $img)
    {
        $size = \getimagesize($img);
        $data = \file_get_contents($img);
        $this->append($data, $size[0], $size[1], $size['bits']);
    }
}

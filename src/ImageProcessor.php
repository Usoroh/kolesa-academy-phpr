<?php

namespace src;

use Imagick;

class ImageProcessor
{
    public function processImage($url, $size, $cropping): Imagick
    {
        $imageData = file_get_contents($url);
        $image = new Imagick();
        $image->readImageBlob($imageData);
        $dimensions = explode("x", $size);
        $height = $dimensions[0];
        $width = $dimensions[1];
        $image->thumbnailImage($width, $height);
        return $image;
    }
}
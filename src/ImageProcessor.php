<?php

namespace src;

class ImageProcessor
{
    public function processImage($url, $size, $cropping){
        $imageData = file_get_contents($url);

        return $imageData;
    }
}
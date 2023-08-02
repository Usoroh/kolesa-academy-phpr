<?php

namespace src;

use Imagick;

class ImageProcessor
{
    public function processImage($url, $size, $cropping): Imagick
    {
        //грузим данные по ссылке на картинку и создаем объект imagick с которым будем работать
        $imageData = file_get_contents($url);
        $image = new Imagick();
        $image->readImageBlob($imageData);

        //парсим высоту и ширину из size
        $dimensions = explode("x", $size);
        $height = $dimensions[0];
        $width = $dimensions[1];

        if ($cropping == 0) {
            //создаем белое полотно куда будем накладывать отмасштабированное изображение
            $canvas = new Imagick();
            $canvas->newImage($width, $height, 'white', 'jpg');

            //масштабируем изображение сохраняя пропорции
            if($height > $width) {
                $image->scaleImage(0, $width);
            } else if ($width > $height) {
                $image->scaleImage($height, 0);
            } else {
                $image->scaleImage($width, $height);
            }

            //получаем размер отмасштабированного изображения
            $imageWidth = $image->getImageWidth();
            $imageHeight = $image->getImageHeight();

            //рассчитываем координаты центра
            $x = ($width - $imageWidth) / 2;
            $y = ($height - $imageHeight) / 2;

            //накладываем изображение по центру полотна и возвращаем результат
            $canvas->compositeImage($image, Imagick::COMPOSITE_OVER, $x, $y);

            return $canvas;
        } else {
            //обрезаем изображение и возвращаем его
            $image->cropThumbnailImage($width, $height);
            return $image;
        }
    }
}
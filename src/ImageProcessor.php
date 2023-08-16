<?php

namespace src;

use Imagick;
use Predis\Client;

class ImageProcessor
{
    private $redis;

    /**
     * Конструктор класса ImageProcessor
     *
     * @param Client $redis Клиент Redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Обработка изображения
     *
     * @param string $imageData Данные изображения
     * @param string $size Размер изображения
     * @param int $cropping Параметр обрезки изображения
     *
     * @return Imagick
     */
    public function processImage($imageData, $size, $cropping): Imagick
    {
        // Генерируем уникального ключа на основе данных изображения, размера и параметра обрезки
        $cacheKey = sha1($imageData . $size . $cropping);

        // Проверяем наличия сжатого изображения в кеше
        if ($cachedImage = $this->redis->get($cacheKey)) {
            $image = new Imagick();
            $image->readImageBlob($cachedImage);
            return $image;
        }

        $image = new Imagick();
        $image->readImageBlob($imageData);

        // Получаем высоту и ширину из параметра size
        $dimensions = explode("x", $size);
        $height = $dimensions[0];
        $width = $dimensions[1];

        if ($cropping == 0) {
            // Создаем белый холса для наложения масштабированного изображения
            $canvas = new Imagick();
            $canvas->newImage($width, $height, 'white', 'jpg');

            // Масштабируем изображение с сохранением пропорций
            if ($height > $width) {
                $image->scaleImage(0, $width);
            } elseif ($width > $height) {
                $image->scaleImage($height, 0);
            } else {
                $image->scaleImage($width, $height);
            }

            // Получаем размер масштабированного изображения
            $imageWidth = $image->getImageWidth();
            $imageHeight = $image->getImageHeight();

            // Вычисляем координаты центра
            $x = ($width - $imageWidth) / 2;
            $y = ($height - $imageHeight) / 2;

            // Накладываем изображение на центр холста
            $canvas->compositeImage($image, Imagick::COMPOSITE_OVER, $x, $y);
            $result = $canvas;
        } else {
            // Обрезаем изображение
            $image->cropThumbnailImage($width, $height);
            $result = $image;
        }

        // Сохраняем сжатое изображение в Redis
        $this->redis->set($cacheKey, $result->getImageBlob());

        return $result;
    }
}

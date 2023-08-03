<?php

namespace src;

use ErrorException;

class RequestHandler
{
private $url;
private $size;
private $cropping;
private $imageData;

public function __construct() {
    $this->url = $_GET["url"] ?? null;
    $this->size = $_GET["size"] ?? null;
    $this->cropping = $_GET["cropping"] ?? 0;
    $this->imageData = null;
}

//чекаем наличие обязательных параметров
public function validate(): void{
    //проверяем что был передан url
    if (!$this->url) {
        http_response_code(400);
        echo json_encode(["error" => "url - отсутствует обязательный параметр url"], JSON_UNESCAPED_UNICODE);
        exit;
    }
    /*разруливаем кейсы если по url не удалось получить изображение
      get_file_contents триггерит warning который мы превращаем в exception
    */
    set_error_handler(/**
     * @throws ErrorException
     */ function($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return;
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    });

    try {
        $this->imageData = file_get_contents($this->url);
    } catch (ErrorException $e) {
        http_response_code(400);
        echo json_encode(['error' => "url - не удалось получить изображение"], JSON_UNESCAPED_UNICODE);
        exit;
    }
    restore_error_handler();

    //проверяем что по адресу лежит изображение
    $headers = get_headers($this->url, 1);
    if (strpos(implode(' ', (array) $headers['Content-Type']), 'image/') === false){
        http_response_code(400);
        echo json_encode(["error" => "url - по адресу должно быть изображение"], JSON_UNESCAPED_UNICODE);
        exit;
    };

     //проверяем что был передан размер
    if (!$this->size) {
        http_response_code(400);
        echo json_encode(["error" => "size - отсутствует обязательный параметр size"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    //проверяем что размер изображения передан в допустимом формате и диапазоне
    $dimensions = explode("x", $this->size);

    if (count($dimensions) != 2) {
        http_response_code(400);
        echo json_encode(["error" => "size - размер должен быть в формате ВЫСОТАxШИРИНА"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $height = $dimensions[0];
    $width = $dimensions[1];

    if ($width < 256 || $width > 1024 || $height < 256 || $height > 1024) {
        http_response_code(400);
        echo json_encode(["error" => "size - высота и ширина  изображения должен быть в диапазоне 256 - 1024 px"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    //проверяем что параметр обрезания был передан корректно
    if ($this->cropping != 0 && $this->cropping != 1) {
        http_response_code(400);
        echo json_encode(["error" => "cropping - параметр может принимать значения 0 или 1"], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

//геттеры для параметров
public function getImageData() {
    return $this->imageData;
}

public function getSize() {
    return $this->size;
}

public function getCropping(){
    return $this->cropping;
}

}
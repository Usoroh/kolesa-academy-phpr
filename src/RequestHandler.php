<?php

namespace src;

class RequestHandler
{
private string $url;
private string $size;
private int $cropping;

public function __construct() {
    $this->url = $_GET["url"] ?? null;
    $this->size = $_GET["size"] ?? null;
    $this->cropping = $_GET["cropping"] ?? 0;
}

//чекаем наличие обязательных параметров
public function validate(): void{
    if (!$this->url) {
        http_response_code(400);
        echo json_encode(["error" => "отсутствует обязательный параметр url"]);
        exit;
    }

    if (!$this->size) {
        http_response_code(400);
        echo json_encode(["error" => "отсутствует обязательный параметр size"]);
        exit;
    }

    //проверяем чтобы размер изображения передавался в допустим формате и диапазоне
    $dimensions = explode("x", $this->size);

    if (count($dimensions) != 2) {
        http_response_code(400);
        echo json_encode(["error" => "размер должен быть в формате ВЫСОТАxШИРИНА"]);
        exit;
    }

    $height = $dimensions[0];
    $width = $dimensions[1];

    if ($width < 256 || $width > 1024 || $height < 256 || $height > 1024) {
        http_response_code(400);
        echo json_encode(["error" => "высота и ширина  изображения должен быть в диапазоне 256 - 1024 px"]);
        exit;
    }
}

//геттеры для параметров
public function getUrl() {
    return $this->url;
}

public function getSize() {
    return $this->size;
}

public function getCropping(){
    return $this->cropping;
}

}
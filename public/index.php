<?php

require_once __DIR__ . '/../src/RequestHandler.php';
require_once __DIR__ . '/../src/ImageProcessor.php';

try {
    $handler = new src\RequestHandler();
    $handler->validate();

    $processor = new src\ImageProcessor();
    try {
        $image = $processor->processImage($handler->getUrl(), $handler->getSize(), $handler->getCropping());
        header('Content-Type: image/jpeg');
        echo $image;
    } catch (Exception $e) {
        //обрабатываем ошибки с процессингом изображения
        header('Content-type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode(['error' => 'Ошибка обработки изображения' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    //обрабатываем остальные ошибки
    header('Content-type: application/json; charset=utf-8');
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

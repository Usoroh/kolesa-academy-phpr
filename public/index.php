<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/RequestHandler.php';
require_once __DIR__ . '/../src/ImageProcessor.php';

use Predis\Client;

// Конфигурация Redis
$redisConfig = [
    'scheme' => 'tcp',
    'host' => 'redis',
    'port' => 6379,
];
$redis = new Client($redisConfig);

try {
    $handler = new src\RequestHandler();
    $handler->validate();

    $processor = new src\ImageProcessor($redis);
    try {
        $image = $processor->processImage($handler->getImageData(), $handler->getSize(), $handler->getCropping());
        header('Content-Type: image/jpeg');
        echo $image;
    } catch (Exception $e) {
        // Обработка ошибок, связанных с обработкой изображения
        header('Content-type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    // Обработка всех других ошибок
    header('Content-type: application/json; charset=utf-8');
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

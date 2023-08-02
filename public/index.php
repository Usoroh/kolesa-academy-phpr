<?php

require_once __DIR__ . '/../src/RequestHandler.php';
require_once __DIR__ . '/../src/ImageProcessor.php';

use src\ImageProcessor;
use src\RequestHandler;

try {
    $handler = new RequestHandler();
    $handler->validate();

    $processor = new ImageProcessor();
    $image = $processor->processImage($handler->getUrl(), $handler->getSize(), $handler->getCropping());

    header('Content-Type: image/jpeg');
    echo $image;
} catch (Exception $e) {
    header('Content-type: application/json; charset=utf-8');
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

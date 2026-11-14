<?php

header('Content-Type: application/json');
http_response_code(200);
echo json_encode([
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'environment' => env('APP_ENV', 'production'),
    'version' => app()->version(),
]);

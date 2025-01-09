<?php
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        return false; 
    }
    if (strpos($_SERVER['REQUEST_URI'], '/actions.php') !== false) {
        require __DIR__ . '/views/actions.php';
        return;
    }
}

http_response_code(404);
require __DIR__ . '/views/404.php';

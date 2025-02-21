<?php 
  function custom_cors_headers() {
    $allowed_origins = [
        'http://127.0.0.1:5500',
        'https://via-apia.isabelamribeiro.com.br'
    ];

    if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
        header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    }

    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        exit();
    }
}

add_action('init', 'custom_cors_headers');

?>
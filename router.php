<?php
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if (preg_match('/\.(txt|md|zip)$/i', $path) || strpos($path, '..') !== false || strpos($path, '/.') !== false) {
    http_response_code(403); exit('Forbidden');
}
return false;

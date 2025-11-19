<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers:  *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    die(); // CORS makes me want to kms, like if you agree
}
$env = parse_ini_file(__DIR__ . '/../.env');
if ($env['MODEL']) {
    echo $env['MODEL'];
} else {
    echo 'Unknown model';
}
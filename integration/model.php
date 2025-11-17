<?php
$env = parse_ini_file(__DIR__ . '/../.env');
if ($env['MODEL']) {
    echo $env['MODEL'];
} else {
    echo 'Unknown model';
}
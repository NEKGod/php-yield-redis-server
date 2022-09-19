<?php
require_once "vendor/autoload.php";

$ip = '127.0.0.1';
$port = 8000;
$cli = new \core\Cli();
$cli->start("tcp://{$ip}:{$port}");
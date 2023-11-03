<?php

define('APP_PATH', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';

use App\kernel\App;

$app = new App();

$app->run();

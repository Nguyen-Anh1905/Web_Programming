<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'app');
define('PUBLIC_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'public');

require_once APP_PATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Autoloader.php';

App\Core\Autoloader::register();

$application = new App\Core\Application();
$application->run();

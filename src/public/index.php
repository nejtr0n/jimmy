<?php declare(strict_types=1);

use DI\Container;
use Laminas\Diactoros\ServerRequestFactory;
use Jimmy\App\Contract;

require __DIR__.'/../vendor/autoload.php';

// env
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

/** @var Container $container */
$container = require_once __DIR__.'/../bootstrap/container.php';

$app = $container->get(Contract::class);

$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
$app->handle($request);





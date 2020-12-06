<?php declare(strict_types=1);

use Jimmy\Ui\Http\Rest\Controllers\ArticlesController;
use Jimmy\Ui\Http\Rest\Middleware\AuthMiddleware;
use Laminas\Diactoros\ResponseFactory;
use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Container\ContainerInterface;

// router factory
return function (ContainerInterface $container): Router {
    $router = new Router();

    $jsonStrategy = new JsonStrategy(new ResponseFactory());
    $router->group(API_PREFIX, function (RouteGroup $api) use ($container) {
        $auth = $container->get(AuthMiddleware::class);
        $controller = $container->get(ArticlesController::class);
        $api->map('GET', '/articles', [$controller, "getList"]);
        $api->map('GET', '/articles/{id:number}', [$controller, "getItem"]);
        $api->map('DELETE', '/articles/{id:number}', [$controller, "deleteItem"])
            ->middleware($auth);
        $api->map('POST', '/articles', [$controller, "createItem"])
            ->middleware($auth);
        $api->map('PUT', '/articles', [$controller, "updateItem"])
            ->middleware($auth);
    })->setStrategy($jsonStrategy);

    return $router;
};

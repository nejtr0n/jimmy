<?php declare(strict_types=1);

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Jimmy\App\Contract as AppContract;
use Jimmy\App\Kernel as AppKernel;
use Jimmy\Domain\Repository\ArticlesRepository as DomainArticlesRepositoryAlias;
use Jimmy\Infrastructure\Persistence\Doctrine\ArticlesRepository;
use Jimmy\Ui\Http\Rest\Middleware\AuthMiddleware;
use JMS\Serializer\Serializer;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use League\Route\Router;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use function Di\create;
use function Di\factory;
use function Di\get;

require_once "constants.php";

return [
    LoggerInterface::class => create(Logger::class),
    EmitterInterface::class => create(SapiStreamEmitter::class),
    Router::class => factory(require __DIR__ . '/routes.php'),
    AppContract::class => get(AppKernel::class),
    AuthMiddleware::class => factory(function (): AuthMiddleware {
        return new AuthMiddleware(API_AUTH);
    }),
    EntityManagerInterface::class => factory(function () {
        return EntityManager::create(
            DriverManager::getConnection([
                'dbname' => getenv("DB_DATABASE"),
                'user' => getenv("DB_USERNAME"),
                'password' => getenv("DB_PASSWORD"),
                'host' => getenv("DB_HOST"),
                'driver' => 'pdo_pgsql',
            ], new Configuration())
            , Setup::createAnnotationMetadataConfiguration([__DIR__ . "/../jimmy/Domain/Model"], true, null, null,
            false));
    }),
    DomainArticlesRepositoryAlias::class => get(ArticlesRepository::class),
    Serializer::class => factory(function () {
        return JMS\Serializer\SerializerBuilder::create()->build();
    }),
];

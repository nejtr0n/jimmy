<?php declare(strict_types=1);

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ );
$dotenv->load();

/** @var \DI\Container $container */
$container = require_once __DIR__.'/bootstrap/container.php';

$entityManager = $container->get(EntityManagerInterface::class);

return DependencyFactory::fromEntityManager(new PhpFile('config/migrations.php'), new ExistingEntityManager($entityManager));

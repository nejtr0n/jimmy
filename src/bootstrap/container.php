<?php declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationRegistry;

// annotations
AnnotationRegistry::registerLoader('class_exists');

// di
$config = require __DIR__ . '/../config/container.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions($config);

return $containerBuilder->build();
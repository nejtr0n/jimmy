<?php declare(strict_types=1);

namespace Tests;

use Psr\Container\ContainerInterface;

abstract class BaseTest extends \Codeception\Test\Unit
{
    protected ContainerInterface $container;

    protected function _setUp()
    {
        $this->container = $this->getModule('\Helper\Phpdi')->getDi();
        parent::_setUp();
    }
}
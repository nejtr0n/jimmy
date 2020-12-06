<?php declare(strict_types=1);

namespace Helper;

use Psr\Container\ContainerInterface;

class Phpdi extends \Codeception\Module
{
    private ContainerInterface $di;

    public function _initialize()
    {
        $this->di = require __DIR__.'/../../../bootstrap/container.php';
    }

    public function getDi() : ContainerInterface
    {
        return $this->di;
    }
}
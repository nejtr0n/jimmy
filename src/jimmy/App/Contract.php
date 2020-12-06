<?php declare(strict_types=1);

namespace Jimmy\App;

use Psr\Http\Message\ServerRequestInterface;

interface Contract
{
    public function handle(ServerRequestInterface $request);
}
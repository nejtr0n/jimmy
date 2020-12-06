<?php declare(strict_types=1);

namespace Jimmy\App;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\Router;
use Psr\Http\Message\ServerRequestInterface;

class Kernel implements Contract
{
    public function __construct(
        private Router $router,
        private EmitterInterface $emitter,
    ) {
    }

    public function handle(ServerRequestInterface $request)
    {
        $response = $this->router->dispatch($request);
        $this->emitter->emit($response);
    }
}
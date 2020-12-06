<?php declare(strict_types=1);

namespace Jimmy\Ui\Http\Rest\Middleware;

use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public const HEADER = "auth";
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $auth = $request->getHeaderLine(self::HEADER);
        if ($auth == $this->key) {
            return $handler->handle($request);
        }
        throw new UnauthorizedException;
    }
}
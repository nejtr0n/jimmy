<?php declare(strict_types=1);

namespace Tests\unit\Ui\Http\Rest\Middleware;


use Jimmy\Ui\Http\Rest\Middleware\AuthMiddleware;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\BaseTest;

class AuthMiddlewareTest extends BaseTest
{
    public function testAuthFails()
    {
        $middleware = new AuthMiddleware("secret");
        $request = new ServerRequest();
        $next = new class() implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response();
            }
        };
        $this->expectException(UnauthorizedException::class);
        $middleware->process($request, $next);
    }

    public function testAuthFailsWithWrongPassword()
    {
        $middleware = new AuthMiddleware("secret");
        $request = (new ServerRequest())->withAddedHeader(AuthMiddleware::HEADER, "wrong");
        $next = new class() implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response();
            }
        };
        $this->expectException(UnauthorizedException::class);
        $middleware->process($request, $next);
    }

    public function testAuthSuccess()
    {
        $middleware = new AuthMiddleware("secret");
        $request = (new ServerRequest())->withAddedHeader(AuthMiddleware::HEADER, "secret");
        $response = new Response();
        $next = new class($response) implements RequestHandlerInterface {
            /**
             * @var ResponseInterface
             */
            private ResponseInterface $response;

            public function __construct(ResponseInterface $response)
            {
                $this->response = $response;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return $this->response;
            }
        };
        $result = $middleware->process($request, $next);
        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertSame($response, $result);
    }
}
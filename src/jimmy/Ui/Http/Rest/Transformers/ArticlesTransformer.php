<?php declare(strict_types=1);

namespace Jimmy\Ui\Http\Rest\Transformers;

use JMS\Serializer\Serializer;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

class ArticlesTransformer
{
    private Serializer $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function transform(mixed $data) : ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(
            $this->serializer->serialize($data, "json")
        );
        return $response
            ->withAddedHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
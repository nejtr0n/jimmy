<?php declare(strict_types=1);

namespace Jimmy\Ui\Http\Rest\Controllers;

use Jimmy\App\Services\ArticlesService;
use Jimmy\Ui\Http\Rest\Serializers\ArticlesSerializer;
use Jimmy\Ui\Http\Rest\Transformers\ArticlesTransformer;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ArticlesController extends BaseController
{
    public function __construct(
        private ArticlesSerializer $serializer,
        private ArticlesService $service,
        private ArticlesTransformer $transformer,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/articles",
     *     description="Returns all articles",
     *     operationId="getList",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="maximum number of results to return",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="offset of first item in result",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="sort order",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"asc", "desc"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="articles response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     )
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function getList(ServerRequestInterface $request): ResponseInterface
    {
        $command = $this->serializer->getListCommand($request);
        $articles = $this->service->getList($command);
        return $this->transformer->transform($articles);
    }

    /**
     * @OA\Get(
     *     path="/articles/{id}",
     *     description="Get article by id",
     *     operationId="getItem",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="article id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="articles response",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     )
     * )
     * @param ServerRequestInterface $request
     * @param array $args
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function getItem(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $command = $this->serializer->getItemCommand($args);
        $article = $this->service->findById($command);
        return $this->transformer->transform($article);
    }

    /**
     * @OA\Delete (
     *     path="/articles/{id}",
     *     description="Remove article by id",
     *     operationId="deleteItem",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="article id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="articles response",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     * @param ServerRequestInterface $request
     * @param array $args
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function deleteItem(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $command = $this->serializer->deleteItemCommand($args);
        $this->service->removeById($command);
        return $this->transformer->transform(["status" => "ok"]);
    }

    /**
     * @OA\Post (
     *     path="/articles",
     *     description="Create article",
     *     operationId="createItem",
     *     @OA\RequestBody(
     *         request="Article",
     *         description="Article description",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/NewArticle"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="articles response",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws BadRequestException
     */
    public function createItem(ServerRequestInterface $request): ResponseInterface
    {
        $command = $this->serializer->createItemCommand($request);
        $article = $this->service->create($command);
        return $this->transformer->transform($article);
    }

    /**
     * @OA\Put  (
     *     path="/articles",
     *     description="Update article",
     *     operationId="updateItem",
     *     @OA\RequestBody(
     *         request="Article",
     *         description="Article description",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Article"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="articles response",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws BadRequestException
     */
    public function updateItem(ServerRequestInterface $request): ResponseInterface
    {
        $command = $this->serializer->updateItemCommand($request);
        $article = $this->service->update($command);
        return $this->transformer->transform($article);
    }
}
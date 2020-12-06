<?php declare(strict_types=1);

namespace Jimmy\Ui\Http\Rest\Serializers;

use Jimmy\App\Commands\Articles\CreateItem;
use Jimmy\App\Commands\Articles\DeleteItem;
use Jimmy\App\Commands\Articles\GetItem;
use Jimmy\App\Commands\Articles\GetList;
use Jimmy\App\Commands\Articles\UpdateItem;
use JMS\Serializer\Serializer;
use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Message\ServerRequestInterface;

class ArticlesSerializer
{
    private array $sorts = ["asc", "desc"];

    public function __construct(
        private Serializer $serializer,
    ) {}

    public function getListCommand(ServerRequestInterface $request) : GetList
    {
        $params = $request->getQueryParams();
        $sort = array_key_exists("sort", $params) ? strtolower($params["sort"]) : "";
        $limit = array_key_exists("limit", $params) ? (int) $params["limit"] : 0;
        $offset = array_key_exists("offset", $params) ? (int) $params["offset"] : 0;

        $command = new GetList();
        if (!empty($sort) && in_array($sort, $this->sorts)) {
            $command->sort = $sort;
        }
        if ($limit > 0) {
            $command->limit = $limit;
        }
        if ($offset > 0) {
            $command->offset = $offset;
        }
        return $command;
    }

    public function getItemCommand(array $args) : GetItem
    {
        $command = new GetItem();
        $id = $this->getId($args);
        if ($id > 0) {
            $command->id = $id;
        }
        return $command;
    }

    public function deleteItemCommand(array $args) : DeleteItem
    {
        $command = new DeleteItem();
        $id = $this->getId($args);
        if ($id > 0) {
            $command->id = $id;
        }
        return $command;
    }

    /**
     * @param ServerRequestInterface $request
     * @return CreateItem
     * @throws BadRequestException
     */
    public function createItemCommand(ServerRequestInterface $request) : CreateItem
    {
        /** @var CreateItem $command */
        $command = $this->serializer->deserialize($request->getBody()->getContents(), CreateItem::class, 'json');
        if (strlen($command->name) > 0) {
            return $command;
        }
        throw new BadRequestException($message = "please provide article name");
    }

    /**
     * @param ServerRequestInterface $request
     * @return CreateItem
     * @throws BadRequestException
     */
    public function updateItemCommand(ServerRequestInterface $request) : UpdateItem
    {
        $command = $this->serializer->deserialize($request->getBody()->getContents(), UpdateItem::class, 'json');
        if (empty($command->id)) {
            throw new BadRequestException($message = "please provide article id");
        }
        if (empty($command->name)) {
            throw new BadRequestException($message = "please provide article name");
        }
        return $command;
    }

    /**
     * @param array $args
     * @return int
     */
    private function getId(array $args) : int
    {
        return array_key_exists("id", $args) ? (int)$args["id"] : -1;
    }
}
<?php declare(strict_types=1);

namespace Tests\unit\Ui\Http\Rest\Serializers;

use Jimmy\App\Commands\Articles\CreateItem;
use Jimmy\App\Commands\Articles\UpdateItem;
use Jimmy\Ui\Http\Rest\Serializers\ArticlesSerializer;
use JMS\Serializer\Exception\RuntimeException;
use Laminas\Diactoros\ServerRequest;
use League\Route\Http\Exception\BadRequestException;
use Tests\BaseTest;

class ArticlesSerializerTest extends BaseTest
{
    protected ArticlesSerializer $serializer;

    protected function _before()
    {
        $this->serializer = $this->container->get(ArticlesSerializer::class);
    }

    protected function _after()
    {
        unset($this->serializer);
    }

    public function testGetListCommand()
    {
        $request = new ServerRequest();

        $command = $this->serializer->getListCommand($request->withQueryParams(["sort" => "asc"]));
        $this->assertSame("asc", $command->sort);

        $command = $this->serializer->getListCommand($request->withQueryParams(["sort"  => "desc"]));
        $this->assertSame("desc", $command->sort);

        $command = $this->serializer->getListCommand($request->withQueryParams(["sort" => "invalid"]));
        $this->assertSame("", $command->sort);

        $command = $this->serializer->getListCommand($request->withQueryParams(["limit" => 0]));
        $this->assertSame(5, $command->limit);

        $command = $this->serializer->getListCommand($request->withQueryParams(["limit" => 1]));
        $this->assertSame(1, $command->limit);

        $command = $this->serializer->getListCommand($request->withQueryParams(["limit" => "invalid"]));
        $this->assertSame(5, $command->limit);

        $command = $this->serializer->getListCommand($request->withQueryParams(["offset" => 0]));
        $this->assertSame(0, $command->offset);

        $command = $this->serializer->getListCommand($request->withQueryParams(["offset" => 15]));
        $this->assertSame(15, $command->offset);

        $command = $this->serializer->getListCommand($request->withQueryParams(["offset" => "invalid"]));
        $this->assertSame(0, $command->offset);
    }

    public function testGetItemCommand()
    {
        $command = $this->serializer->getItemCommand([]);
        $this->assertSame(-1, $command->id);

        $command = $this->serializer->getItemCommand(["id" => "0"]);
        $this->assertSame(-1, $command->id);

        $command = $this->serializer->getItemCommand(["id" => "1"]);
        $this->assertSame(1, $command->id);

        $command = $this->serializer->getItemCommand(["id" => "wrong"]);
        $this->assertSame(-1, $command->id);
    }

    public function testDeleteItemCommand()
    {
        $command = $this->serializer->deleteItemCommand([]);
        $this->assertSame(-1, $command->id);

        $command = $this->serializer->deleteItemCommand(["id" => "0"]);
        $this->assertSame(-1, $command->id);

        $command = $this->serializer->deleteItemCommand(["id" => "1"]);
        $this->assertSame(1, $command->id);

        $command = $this->serializer->deleteItemCommand(["id" => "wrong"]);
        $this->assertSame(-1, $command->id);
    }

    public function testCreateItemCommandBadRequest()
    {
        $request = new ServerRequest();
        $this->expectException(RuntimeException::class);
        $this->serializer->createItemCommand($request);
    }

    public function testCreateItemCommandEmptyJson()
    {
        $request = new ServerRequest([], [], null, null, 'php://memory');
        $body = $request->getBody();
        $body->write("{}");
        $body->rewind();
        $this->expectException(BadRequestException::class);
        $this->serializer->createItemCommand($request);
    }

    public function testCreateItemCommandEmptyArticleName()
    {
        $request = new ServerRequest([], [], null, null, 'php://memory');
        $body = $request->getBody();
        $body->write('{"name": ""}');
        $body->rewind();
        $this->expectException(BadRequestException::class);
        $this->serializer->createItemCommand($request);
    }

    public function testCreateItemCommand()
    {
        $request = new ServerRequest([], [], null, null, 'php://memory');
        $body = $request->getBody();
        $body->write('{"name": "test"}');
        $body->rewind();
        $command = $this->serializer->createItemCommand($request);
        $this->assertInstanceOf(CreateItem::class, $command);
        $this->assertSame("test", $command->name);
    }

    public function testUpdateItemCommandBadRequest()
    {
        $request = new ServerRequest();
        $this->expectException(RuntimeException::class);
        $this->serializer->updateItemCommand($request);
    }

    public function testUpdateItemCommandEmptyJson()
    {
        $request = new ServerRequest([], [], null, null, 'php://memory');
        $body = $request->getBody();
        $body->write("{}");
        $body->rewind();
        $this->expectException(BadRequestException::class);
        $this->serializer->updateItemCommand($request);
    }

    public function testUpdateItemCommandEmptyName()
    {
        $request = new ServerRequest([], [], null, null, 'php://memory');
        $body = $request->getBody();
        $body->write('{"id": 1}');
        $body->rewind();
        $this->expectException(BadRequestException::class);
        $this->serializer->updateItemCommand($request);
    }

    public function testUpdateItemCommandEmptyId()
    {
        $request = new ServerRequest([], [], null, null, 'php://memory');
        $body = $request->getBody();
        $body->write('{"name": "test"}');
        $body->rewind();
        $this->expectException(BadRequestException::class);
        $this->serializer->updateItemCommand($request);
    }

    public function testUpdateItemCommand()
    {
        $request = new ServerRequest([], [], null, null, 'php://memory');
        $body = $request->getBody();
        $body->write('{"id": 1, "name": "test"}');
        $body->rewind();
        $command = $this->serializer->updateItemCommand($request);
        $this->assertInstanceOf(UpdateItem::class, $command);
        $this->assertSame(1, $command->id);
        $this->assertSame("test", $command->name);
    }
}
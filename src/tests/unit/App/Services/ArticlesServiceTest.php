<?php declare(strict_types=1);

namespace Tests\unit\App\Services;

use DateTime;
use Jimmy\App\Commands\Articles\CreateItem;
use Jimmy\App\Commands\Articles\DeleteItem;
use Jimmy\App\Commands\Articles\GetItem;
use Jimmy\App\Commands\Articles\UpdateItem;
use Jimmy\App\Services\ArticlesService;
use Jimmy\Domain\Model\Article;
use Jimmy\Domain\Repository\ArticlesRepository;
use League\Route\Http\Exception\NotFoundException;
use Tests\BaseTest;

class ArticlesServiceTest  extends BaseTest
{
    public function testFindByIdFails()
    {
        $repository = $this->makeEmpty(ArticlesRepository::class, ['findById' => function (int $id) { return null; }]);
        $service = new ArticlesService($repository);
        $command = new GetItem();
        $command->id = 1;
        $this->expectException(NotFoundException::class);
        $service->findById($command);
    }

    public function testFindByIdSuccess()
    {
        $article = $this->makeEmpty(Article::class);
        $repository = $this->makeEmpty(ArticlesRepository::class, ['findById' => function (int $id) use ($article) { return $article; }]);
        $service = new ArticlesService($repository);
        $command = new GetItem();
        $command->id = 1;
        $result = $service->findById($command);
        $this->assertInstanceOf(Article::class, $result);
        $this->assertSame($article, $result);
    }

    public function testRemoveByIdFailes()
    {
        $repository = $this->makeEmpty(ArticlesRepository::class, ['removeById' => function (int $id) { return null; }]);
        $service = new ArticlesService($repository);
        $command = new DeleteItem();
        $command->id = 1;
        $this->expectException(NotFoundException::class);
        $service->removeById($command);
    }

    public function testRemoveByIdSuccess()
    {
        $article = $this->makeEmpty(Article::class);
        $repository = $this->makeEmpty(ArticlesRepository::class, ['removeById' => function (int $id) use ($article) { return $article; }]);
        $service = new ArticlesService($repository);
        $command = new DeleteItem();
        $command->id = 1;
        $result = $service->removeById($command);
        $this->assertInstanceOf(Article::class, $result);
        $this->assertSame($article, $result);
    }

    public function testCreate()
    {
        $id = 1;
        $date = new DateTime();
        $repository = $this->makeEmpty(ArticlesRepository::class, ['createOrUpdate' => function (Article $article) use ($id, $date) : Article {
            $article->setId($id);
            $article->setUpdatedAt($date);
            $article->setCreatedAt($date);
            return $article;
        }]);
        $service = new ArticlesService($repository);
        $command = new CreateItem();
        $command->name = "test";
        $result = $service->create($command);
        $this->assertSame($id, $result->getId());
        $this->assertSame($command->name, $result->getName());
        $this->assertSame($date, $result->getCreatedAt());
        $this->assertSame($date, $result->getCreatedAt());
    }

    public function testUpdateFails()
    {
        $repository = $this->makeEmpty(ArticlesRepository::class, ['findById' => function (int $id) { return null; }]);
        $service = new ArticlesService($repository);
        $command = new UpdateItem();
        $command->id = 1;
        $this->expectException(NotFoundException::class);
        $service->update($command);
    }

    public function testUpdateSuccess()
    {
        $id = 1;
        $name = "test";
        $old = DateTime::createFromFormat('Y-m-d', '2009-02-15');
        $new = DateTime::createFromFormat('Y-m-d', '2011-02-15');
        $existing = $this->make(Article::class, ['id' => $id, 'name' => 'to change', 'createdAt' => $old, 'updatedAt' => $old]);
        $repository = $this->makeEmpty(ArticlesRepository::class, [
            'findById' => function (int $id) use ($existing) {
                 return $existing;
            },
            'createOrUpdate' => function (Article $article) use ($new) : Article {
                $article->setUpdatedAt($new);
                $article->setCreatedAt($new);
                return $article;
            },
        ]);
        $service = new ArticlesService($repository);
        $command = new UpdateItem();
        $command->id = 1;
        $command->name = "test";
        $result = $service->update($command);
        $this->assertInstanceOf(Article::class, $result);
        $this->assertSame($id, $result->getId());
        $this->assertSame($name, $result->getName());
        $this->assertSame($new, $result->getCreatedAt());
        $this->assertSame($new, $result->getUpdatedAt());
    }
}
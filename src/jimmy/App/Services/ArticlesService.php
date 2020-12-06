<?php declare(strict_types=1);

namespace Jimmy\App\Services;

use Jimmy\App\Commands\Articles\CreateItem;
use Jimmy\App\Commands\Articles\DeleteItem;
use Jimmy\App\Commands\Articles\GetItem;
use Jimmy\App\Commands\Articles\GetList;
use Jimmy\App\Commands\Articles\UpdateItem;
use Jimmy\Domain\Model\Article;
use Jimmy\Domain\Repository\ArticlesRepository;
use League\Route\Http\Exception\NotFoundException;

class ArticlesService
{
    /**
     * ArticlesService constructor.
     * @param ArticlesRepository $repository
     */
    public function __construct(
        private ArticlesRepository $repository,
    ) {
    }

    public function getList(GetList $command): array
    {
        return $this->repository->getList($command->sort, $command->offset, $command->limit);
    }

    public function findById(GetItem $command): Article
    {
        $article = $this->repository->findById($command->id);
        if (is_null($article)) {
            throw new NotFoundException;
        }
        return $article;
    }

    public function removeById(DeleteItem $command): Article
    {
        $article = $this->repository->removeById($command->id);
        if (is_null($article)) {
            throw new NotFoundException;
        }
        return $article;
    }

    public function create(CreateItem $command): Article
    {
        $article = new Article();
        $article->setName($command->name);
        $article = $this->repository->createOrUpdate($article);
        return $article;
    }

    public function update(UpdateItem $command): Article
    {
        $article = $this->repository->findById($command->id);
        if (is_null($article)) {
            throw new NotFoundException;
        }
        $article->setName($command->name);
        $article = $this->repository->createOrUpdate($article);
        return $article;
    }
}
<?php declare(strict_types=1);

namespace Jimmy\Domain\Repository;

use Jimmy\Domain\Model\Article;

interface ArticlesRepository
{
    public function getList(string $sort, int $offset, int $limit);

    public function findById(int $id): ?Article;

    public function removeById(int $id): ?Article;

    public function createOrUpdate(Article $article): Article;
}
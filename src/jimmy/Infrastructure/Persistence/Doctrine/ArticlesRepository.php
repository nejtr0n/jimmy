<?php declare(strict_types=1);

namespace Jimmy\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Jimmy\Domain\Model\Article;
use Jimmy\Domain\Repository\ArticlesRepository as DomainArticlesRepositoryAlias;

class ArticlesRepository implements DomainArticlesRepositoryAlias
{
    /**
     * @var ObjectRepository
     */
    private ObjectRepository $repository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * ArticlesRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Article::class);
    }

    /**
     * @param string $sort
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getList(string $sort, int $offset, int $limit) : array
    {
        $arrSort = [];
        if (strlen($sort) > 0) {
            $arrSort = ["name" => $sort ];
        }
        return $this->repository->findBy([], $arrSort, $limit, $offset);
    }

    /**
     * @param int $id
     * @return ?Article
     */
    public function findById(int $id) : ?Article
    {
        return $this->repository->find($id);
    }

    /**
     * @param int $id
     * @return Article|null
     */
    public function removeById(int $id): ?Article
    {
        $article = $this->repository->find($id);
        if (!is_null($article)) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }
        return $article;
    }

    public function createOrUpdate(Article $article): Article
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return $article;
    }
}
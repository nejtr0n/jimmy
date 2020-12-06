<?php declare(strict_types=1);

namespace Tests\unit\Ui\Http\Rest\Transformers;

use DateTimeInterface;
use Jimmy\Domain\Model\Article;
use Jimmy\Ui\Http\Rest\Transformers\ArticlesTransformer;
use Psr\Http\Message\ResponseInterface;
use Tests\BaseTest;

class ArticlesTransformerTest extends BaseTest
{
    /**
     * @var ArticlesTransformer
     */
    private ArticlesTransformer $transformer;

    protected function _before()
    {
        $this->transformer = $this->container->get(ArticlesTransformer::class);
    }

    public function testTransform()
    {
        foreach ([
            [
                "data" => [
                    $this->buildArticle(1, "test", \DateTime::createFromFormat('Y-m-d', '2009-02-15')),
                    $this->buildArticle(2, "test", \DateTime::createFromFormat('Y-m-d', '2009-02-15'), \DateTime::createFromFormat('Y-m-d', '2009-02-15')),
                ],
                "result" => '[{"id":1,"name":"test","created_at":"2009-02-15"},{"id":2,"name":"test","created_at":"2009-02-15","updated_at":"2009-02-15"}]'
            ],
            [
                "data" => $this->buildArticle(1, "test", \DateTime::createFromFormat('Y-m-d', '2009-02-15')),
                "result" => '{"id":1,"name":"test","created_at":"2009-02-15"}',
            ]
        ] as $case) {
            $res = $this->transformer->transform($case["data"]);
            $this->assertEquals(200, $res->getStatusCode());
            $this->assertEquals("application/json", $res->getHeaderLine("content-type"));
            $this->assertEquals($case["result"], $this->getResponseContent($res));
        }
    }

    private function getResponseContent(ResponseInterface $response) : string
    {
        $body = $response->getBody();
        $body->rewind();
        return $body->getContents();
    }

    private function buildArticle(int $id, string $name, DateTimeInterface $createdAt, DateTimeInterface $updatedAt = null) : Article
    {
        $article = new Article();
        $article->setId($id);
        $article->setName($name);
        $article->setCreatedAt($createdAt);
        $article->setUpdatedAt($updatedAt);
        return $article;
    }
}
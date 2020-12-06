<?php declare(strict_types=1);

namespace Jimmy\Domain\Model;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="NewArticle", required={"name"})
 * @ORM\Entity
 * @ORM\Table(name="articles")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Type("integer")
     */
    protected ?int $id = null;
    /**
     * @OA\Property
     * @ORM\Column(type="string")
     * @Type("string")
     */
    protected string $name;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

/**
 * @OA\Schema(
 *   schema="Article",
 *   type="object",
 *   allOf={
 *       @OA\Schema(ref="#/components/schemas/NewArticle"),
 *       @OA\Schema(
 *           required={"id"},
 *           @OA\Property(property="id", format="int64", type="integer")
 *       )
 *   }
 * )
 */
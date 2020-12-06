<?php declare(strict_types=1);

namespace Jimmy\App\Commands\Articles;

use JMS\Serializer\Annotation\Type;

class UpdateItem
{
    /**
     * @Type("integer")
     */
    public ?int $id = null;
    /**
     * @Type("string")
     */
    public string $name = "";
}
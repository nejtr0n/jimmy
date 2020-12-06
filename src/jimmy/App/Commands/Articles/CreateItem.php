<?php declare(strict_types=1);

namespace Jimmy\App\Commands\Articles;

use JMS\Serializer\Annotation\Type;

class CreateItem
{
    /**
     * @Type("string")
     */
    public string $name = "";

    public function __construct(string $name = "")
    {
        $this->name = $name;
    }
}
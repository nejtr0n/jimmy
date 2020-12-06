<?php declare(strict_types=1);

namespace Jimmy\App\Commands\Articles;

class GetList
{
    public function __construct(
        public string $sort = "",
        public int $offset = 0,
        public int $limit = 5
    ) {
    }
}
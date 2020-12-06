<?php declare(strict_types=1);


namespace Jimmy\App\Commands\Articles;


class DeleteItem
{
    public function __construct(
        public int $id = -1,
    ) {
    }
}
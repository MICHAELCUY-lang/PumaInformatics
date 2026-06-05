<?php

namespace App\Repositories\Contracts;

interface ArticleRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithAuthor(int $perPage = 15);
    public function getLatest(int $limit = 3);
}

<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\BaseRepository;

class ArticleRepository extends BaseRepository
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }
}

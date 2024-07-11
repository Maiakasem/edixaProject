<?php

namespace App\Repositories;

use App\Models\TestTable;

class TestTableRepository extends BaseRepository
{
    public function __construct(TestTable $model)
    {
        parent::__construct($model);
    }

}

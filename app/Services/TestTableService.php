<?php

namespace App\Services;

use App\Repositories\TestTableRepository;

class TestTableService extends BaseService
{
    public function __construct(TestTableRepository $model)
    {
        parent::__construct($model);
    }

}

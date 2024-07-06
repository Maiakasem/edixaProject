<?php

namespace App\Repositories;
use App\Models\Announcement;

class AnnouncementRepository extends BaseRepository
{
    

    public function __construct(Announcement $model)
    {
        parent::__construct($model);
    }


}

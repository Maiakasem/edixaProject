<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\AnnouncementTranslation;

class AnnouncementTranslationRepoistory extends BaseRepository
{
    public function __construct(AnnouncementTranslation $model)
    {
        parent::__construct($model);
    }
}

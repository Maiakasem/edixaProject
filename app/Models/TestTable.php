<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;


class TestTable extends Model {
    
    use HasFactory;
    use Translatable;

    public $searchable = ['name'];

    public $columns = ['user_id'];

    public $translatedAttributes = ['name'];

    protected $guarded = ['id'];

}

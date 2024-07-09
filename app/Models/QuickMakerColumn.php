<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickMakerColumn extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function quickMaker() {
        return $this->belongsTo(QuickMaker::class);
    }
}

<?php

namespace App\Models;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;


class Announcement extends Model implements HasMedia {
    public $searchable = ['description', 'title'];

    public $columns = ['image', 'location', 'open_url_in', 'url'];

    public $translatedAttributes = ['description', 'title'];





    
    use HasFactory;
    use InteractsWithMedia;
    use Translatable;
    protected $guarded=[];

    public function image($type="original"){
        if($this->image==null)
            return env('DEFAULT_IMAGE');
        else
            return env("STORAGE_URL").'/'.\MainHelper::get_conversion($this->image,$type);
    }

    public function getStartDateAttribute($value)
    {
        if($value==null)return;
        return \Carbon::parse($value)->format('Y-m-d\TH:i');
    }
    public function getEndDateAttribute($value)
    {
        if($value==null)return;
        return \Carbon::parse($value)->format('Y-m-d\TH:i');
    }
    public function registerMediaConversions(?Media $media = null): void
    {
       
        $this
            ->addMediaConversion('tiny')
            ->fit( Fit::Contain, 120, 120)
            ->width(120)
            ->format('webp')
            ->nonQueued();

        $this
            ->addMediaConversion('thumb')
            ->fit(Fit::Contain, 350, 1000)
            ->width(350)
            ->format('webp')
            ->nonQueued();

        $this
            ->addMediaConversion('original')
            ->fit(Fit::Contain, 1200,10000)
            ->width(1200)
            ->format('webp')
            ->nonQueued();

    }
}

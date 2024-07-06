<?php

namespace App\Services;

use MainHelper;
use App\Services\BaseService;
use App\Repositories\AnnouncementRepository;

class AnnouncementService extends BaseService
{
    public function __construct(AnnouncementRepository $repo)
    {
        parent::__construct($repo);
    }


    public function create($request)
    {
        
        $baseResponse = parent::create($request->validated());
        if($baseResponse['success']) {
            $announcement = $baseResponse['data'];
            $this->upload_image($request, $announcement);
        }
        return $baseResponse;
    }
    public function update($request, $id)
    {
      
        $baseResponse = parent::update($request->validated(), $id);
        if($baseResponse['success']) {
            $announcement = $baseResponse['data'];
            $this->upload_image($request, $announcement);   
        }
        return $baseResponse;
    }
    public function upload_image($request, $category)  {
        if($request->hasFile('image')){
            $image = $category->addMedia($request->image)->toMediaCollection('image');
            $category->update(['image'=>$image->id.'/'.$image->file_name]);
        }
        MainHelper::move_media_to_model_by_id($request->temp_file_selector,$category,"description");
    }
}

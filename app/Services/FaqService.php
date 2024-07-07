<?php

namespace App\Services;

use App\Repositories\FaqRepository;

class FaqService extends BaseService
{
    public function __construct(FaqRepository $repo)
    {
        parent::__construct($repo);
    }
    public function create($request)
    {
        
        $baseResponse = parent::create($request->validated());
        if($baseResponse['success']) {
            $faqs = $baseResponse['data'];
            $this->upload_image($request, $contacts);
        }
        return $baseResponse;
    }
    public function update($request, $id)
    {
      
        $baseResponse = parent::update($request->validated(), $id);
        if($baseResponse['success']) {
            $faq = $baseResponse['data'];
            $this->upload_image($request, $faq);   
        }
        return $baseResponse;
    }
    public function upload_image($request, $faq)  {
        if($request->hasFile('image')){
            $image = $faq->addMedia($request->image)->toMediaCollection('image');
            $faq->update(['image'=>$image->id.'/'.$image->file_name]);
        }
        MainHelper::move_media_to_model_by_id($request->temp_file_selector,$faq,"description");
    }
}

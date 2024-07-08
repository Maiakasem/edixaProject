<?php

namespace App\Services;

use App\Repositories\ArticleRepository;

class ArticleService extends BaseService
{
    public function __construct(ArticleRepository $repo)
    {
        parent::__construct($repo);
    }
    public function create($request)
    {
        // dd ($request->all());
        // $request->merge(['slug'=>\MainHelper::slug($reques;t->slug)]);
    //    dd($request);
        $baseResponse = parent::create($request->validated()); 
        if($baseResponse['success']) {
            $article = $baseResponse['data'];
            $this->upload_image($request, $article);
        }
        return $baseResponse;
    }
    public function update($request, $id)
    {
      
        $baseResponse = parent::update($request->validated(), $id);
        if($baseResponse['success']) {
            $article = $baseResponse['data'];
            $this->upload_image($request, $article);   
        }
        return $baseResponse;
    }
    public function upload_image($request, $article)  {
        if($request->hasFile('image')){
            $image = $article->addMedia($request->image)->toMediaCollection('image');
            $article->update(['image'=>$image->id.'/'.$image->file_name]);
        }
        MainHelper::move_media_to_model_by_id($request->temp_file_selector,$article,"description");
    }
}

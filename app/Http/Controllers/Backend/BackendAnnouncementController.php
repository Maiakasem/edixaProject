<?php

namespace App\Http\Controllers\Backend;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AnnouncementService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;

class BackendAnnouncementController extends BaseController

{
    
    public function __construct(AnnouncementService $service)
    {  
        parent::__construct($service);
    
        $this->middleware('can:announcements-create', ['only' => ['create','store']]);
        $this->middleware('can:announcements-read',   ['only' => ['show', 'index']]);
        $this->middleware('can:announcements-update',   ['only' => ['edit','update']]);
        $this->middleware('can:announcements-delete',   ['only' => ['delete']]);
    }
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function search(Request $request)
    // {
    //     dd($request);
    //     $announcements=Announcement::where(function($q)use($request){
            
    //         if($request->id!=null)
    //             $q->where('id',$request->id);

    //         $q->where('title','LIKE','%'.$request->key.'%');
    //     })->orderBy('id','DESC')->paginate();

    //     return view('admin.announcements.index');
    // }

    public function index()
    {
        $announcements=Announcement::all();
        return view('admin.announcements.index',compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $translateFields = $this->service->translateFields();
        $columns_fields = $this->service->columnsFields();
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnnouncementRequest $request)
    {
        
        $response = $this->service->create($request);
        
        toastr()->success(__('utils/toastr.store_success_message'));
        return redirect()->route('admin.announcements.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        $translateFields = $this->service->translateFields();
        $columns_fields = $this->service->columnsFields();
        return view('admin.announcements.edit',compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $response = $this->service->update($request->validated(), $announcement->id);
        toastr()->success(__('utils/toastr.update_success_message'));
        return redirect()->route('admin.announcements.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $response = $this->service->delete($announcement->id);
        toastr()->success(__('utils/toastr.destroy_success_message'));
        return redirect()->route('admin.announcements.index');
    }
}

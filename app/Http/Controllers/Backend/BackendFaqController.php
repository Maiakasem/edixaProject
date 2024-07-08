<?php

namespace App\Http\Controllers\Backend;

use App\Models\Faq;
use App\Services\FaqService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqsRequest;
use App\Http\Controllers\BaseController;

class BackendFaqController extends BaseController
{

public function __construct(FaqService $service){
    
        parent::__construct($service);
        $this->middleware('can:faqs-create', ['only' => ['create','store']]);
        $this->middleware('can:faqs-read',   ['only' => ['show', 'index']]);
        $this->middleware('can:faqs-update',   ['only' => ['edit','update']]);
        $this->middleware('can:faqs-delete',   ['only' => ['delete']]);
    
}
   

    public function index(Request $request)
    { 
        $response = $this->service->getPaginated($request);
        if ($response['success']) {
            $faqs = $response['data'];
            return view('admin.faqs.index',compact('faqs'));}
            else {
            abort($response['status'], $response['message']);
        }
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
        return view('admin.faqs.create')->compact('translateFields','columns_fields');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFaqsRequest $request)
    {
        
        $response = $this->service->create($request);
        toastr()->success(__('utils/toastr.process_success_message'), __('utils/toastr.successful_process_message'));
        return redirect()->route('admin.faqs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        $translateFields = $this->service->translate_fields();
        $columns_fields = $this->service->columns_fields();

        return view('admin.faqs.edit',compact('faq','translateFields','columns_fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
        $response = $this->service->update($request->validated(), $faq->id);
        if ($response['success']) {
            toastr()->success(__('utils/toastr.faq_update_success_message'), __('utils/toastr.successful_process_message'));
        } else {
            toastr()->error(__('utils/toastr.failed_process_message'));
        }
       return redirect()->route('admin.faqs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        $response = $this->service->delete($faq->id);
        if ($response['success']) {
            toastr()->success(__('utils/toastr.faq_update_success_message'), __('utils/toastr.successful_process_message'));
        } else {
            toastr()->error(__('utils/toastr.failed_process_message'));
        }
        return redirect()->route('admin.faqs.index');
    }


    public function order(Request $request)
    {
        foreach($request->order as $key => $value){
            Faq::where('id',$value)->update(['order'=>$key]);
        }
    }

}

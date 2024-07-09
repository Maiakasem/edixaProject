@extends('layouts.admin')
@section('content')
    <form action="{{ route('admin.quick-makers.store') }}" class="card" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-lg">
                    <label for="nameFormControlInput" class="mb-2" class="form-label">{{ __('lang.name') }}</label>
                    <input type="text" class="form-control" name="name" id="nameFormControlInput" placeholder="{{ __('lang.name') }}">
                </div>
                <div class="col-lg">
                    <label class="form-label" for="form-1">{{ __('lang.has_migration') }}</label>
                    <select id="form-1" name="has_migration" class=" form-select">
                        <option value="0">{{ __('lang.no') }}</option>
                        <option value="1">{{ __('lang.yes') }}</option>
                    </select>
                </div>
                <div class="col-lg">
                    <label class="form-label" for="form-2">{{ __('lang.has_model') }}</label>
                    <select id="form--2" name="has_model" class=" form-select">
                        <option value="0">{{ __('lang.no') }}</option>
                        <option value="1">{{ __('lang.yes') }}</option>
                    </select>
                </div>
               
                <div class="col-lg">
                    <label class="form-label" for="form-3">{{ __('lang.has_controller') }}</label>
                    <select id="form-3" name="has_controller" class=" form-select">
                        <option value="0">{{ __('lang.no') }}</option>
                        <option value="1">{{ __('lang.yes') }}</option>
                    </select>
                </div>

                <div class="col-lg">
                    <label class="form-label" for="form-3">{{ __('lang.has_blade') }}</label>
                    <select id="form-3" name="has_blade" class=" form-select">
                        <option value="0">{{ __('lang.no') }}</option>
                        <option value="1">{{ __('lang.yes') }}</option>
                    </select>
                </div>
                <div class="col-12">
                    <br>
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label" for="form-4">{{ __('lang.is_module_child') }}</label>
                            <select id="form-4" name="is_module_child" class=" form-select">
                                <option value="0">{{ __('lang.no') }}</option>
                                <option value="1">{{ __('lang.yes') }}</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-6">
                            <label class="form-label" for="form-repeater-1-2">{{ __('lang.module') }}</label>
                            <select disabled id="form-repeater-1-2" name="module" class="form-select">
                                <option value="">
                                    {{ __('lang.no') }}
                                </option>
                               @foreach ($modules as $name => $module)
                               <option value="{{ $name }}">{{$name}}</option>
                               @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
        <hr>
        <h5 class="card-header">{{ __('lang.fields') }}</h5>
        <div class="card-body">
            <div class="form-repeater">
                <div data-repeater-list="group-a">
                    <div data-repeater-item>
                        <div class="row">
                            <div class="mb-3 col-lg-4 mb-0">
                                <label class="form-label" for="form-repeater-1-1">{{ __('lang.name') }}</label>
                                <input type="text" id="form-repeater-1-1" class="form-control"
                                    placeholder="{{ __('lang.name') }}" name="name" />
                            </div>
                            <div class="mb-3 col-lg-4 mb-0">
                                <label class="form-label" for="form-repeater-1-2">{{ __('lang.type') }}</label>
                                <select id="form-repeater-1-2" class="form-select">
                                    @foreach ($types as $type)
                                        <option value="{{ $type->type }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-lg-4 mb-0">
                                <label class="form-label" for="form-repeater-1-3">{{ __('lang.value') }}</label>
                                <input type="text" id="form-repeater-1-3" class="form-control"
                                    placeholder="{{ __('lang.value') }}" name="value" />
                            </div>
                            <div class="mb-3 col-12 mb-0">
                                <div class="row">
                                    <div class="mb-3 col-lg mb-0">
                                        <label class="form-label" for="form-repeater-1-4">{{ __('lang.relation') }}</label>
                                        <select id="form-repeater-1-4" name="relation" class="form-select relationFinder">
                                            <option value="0">{{ __('lang.no') }}</option>
                                            <option value="1">{{ __('lang.yes') }}</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-lg-4 mb-0">
                                        <label class="form-label"
                                        for="form-repeater-1-5">{{ __('lang.relation_model') }}</label>
                                        <select disabled id="form-repeater-1-5" class="form-select relation_model">
                                            @foreach ($models as $model)
                                                <option value="{{ $model }}">{{ $model }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-lg-4 mb-0">
                                        <label class="form-label"
                                            for="form-repeater-1-3">{{ __('lang.relation_key') }}</label>
                                        <input type="text" id="form-repeater-1-3" class="form-control relation_key"
                                            placeholder="{{ __('lang.relation_key') }}" name="relation_key" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="form-repeater-1-2">{{ __('lang.required') }}</label>
                                <select id="form-repeater-1-2" name="required" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="form-repeater-1-2">{{ __('lang.unique') }}</label>
                                <select id="form-repeater-1-2" name="unique" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="form-repeater-1-2">{{ __('lang.searchable') }}</label>
                                <select id="form-repeater-1-2" name="searchable" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="form-repeater-1-2">{{ __('lang.translatable') }}</label>
                                <select id="form-repeater-1-2" name="translatable" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg d-flex align-items-center mb-0">
                                <button class="btn w-100 btn-label-danger mt-4" data-repeater-delete>
                                    <i class="ti ti-x ti-xs me-1"></i>
                                    <span class="align-middle">{{ __('lang.delete') }}</span>
                                </button>
                            </div>
                        </div>
                        <hr />
                    </div>
                </div>
                <div class="mb-0">
                    <button class="btn btn-primary" type="button" data-repeater-create>
                        <i class="ti ti-plus me-1"></i>
                        <span class="align-middle">{{ __('lang.add') }} {{ __('lang.field') }}</span>
                    </button>
                </div>
            </div>
        </div>
        <hr>
        <div class="card-footer">
            <div class="mb-3">
                <button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('lang.save') }}</button>
            </div>
        </div>
    </ب>
    @push('scripts')
        <script src="{{ asset('dashboard/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
        <script src="{{ asset('dashboard/js/forms-extras.js') }}"></script>
        <script>
           $(function () {
            $("select[name='is_module_child']").change(function(e) {
                e.preventDefault();
                if($(this).val() == 1 || $(this).val() == "1") {
                    $("select[name='module']").prop('disabled', false);
                } else {
                    $("select[name='module']").prop('disabled', true);
                }
            }); 
            $(document).on("change",".relationFinder", function(e) {
                if($(this).val() == 1 || $(this).val() == "1") {
                    $(this).parent().parent().find('.relation_model').prop('disabled', false);
                    $(this).parent().parent().find('.relation_key').prop('disabled', false);
                    
                } else {
                    $(this).parent().parent().find('.relation_model').prop('disabled', true);
                    $(this).parent().parent().find('.relation_key').prop('disabled', true);
                }
            }); 

           });
        </script>
    @endpush
@endsection

@extends('layouts.admin')
@section('content')
    <form action="{{ route('admin.quick-makers.store') }}" class="QuickBuilderForm" method="POST">
        <div class="card">
            <div class="card-body">
                <h2>{{ __('lang.new_section') }}</h2>
                <div class="row">
                    <div class="col-lg">
                        <label for="nameFormControlInput" class="mb-2" class="form-label">{{ __('lang.name') }}</label>
                        <input type="text" class="form-control" name="name" id="nameFormControlInput" placeholder="{{ __('lang.name') }}">
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
        </div>
        <div class="card">
            <div class="card-body">
                <h2>{{ __('lang.new_section') }}</h2>
            </div>
        </div>
    </form>
@endsection

{{-- 
    <form action="{{ route('admin.quick-makers.store') }}" class="card QuickBuilderForm" method="POST">
        @csrf
        
        <hr>
        <h3 class="card-header">{{ __('lang.fields') }}</h3>
        <div class="card-body">
            <h5>{{ __('lang.database') }}</h5>
            <div class="form-repeater">
                <div data-repeater-list="group-a">
                    <div data-repeater-item class="repeater-item">
                        <div class="row">
                            <div class="mb-3 col-lg col-md-6 mb-0">
                                <label class="form-label" for="form-repeater-1-1">{{ __('lang.name') }}</label>
                                <input type="text" id="form-repeater-1-1" class="form-control identifier"
                                    placeholder="{{ __('lang.name') }}" name="name" />
                            </div>
                            <div class="mb-3 col-lg mb-0 col-md-6">
                                <label class="form-label" for="form-repeater-1-2">{{ __('lang.value') }}</label>
                                <input type="text" id="form-repeater-1-2" class="form-control"
                                    placeholder="{{ __('lang.value') }}" name="value" />
                            </div>
                            <div class="mb-3 col-lg mb-0 col-md-6">
                                <label class="form-label" for="form-repeater-1-3">{{ __('lang.type') }}</label>
                                <select id="form-repeater-1-4" name="type" class="form-select">
                                    @foreach ($types as $type)
                                        <option value="{{ $type->type }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-lg mb-0 col-md-6">
                                <label class="form-label" for="form-repeater-1-7">{{ __('lang.required') }}</label>
                                <select id="form-repeater-1-7" name="required" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg mb-0 col-md-6">
                                <label class="form-label" for="form-repeater-1-8">{{ __('lang.unique') }}</label>
                                <select id="form-repeater-1-8" name="unique" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <h5>{{ __('lang.relationship') }}</h5>
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
                                <select disabled id="form-repeater-1-5" name="relation_model" class="form-select relation_model">
                                    @foreach ($models as $model)
                                        <option value="{{ $model }}">{{ $model }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-lg-4 mb-0">
                                <label class="form-label"
                                    for="form-repeater-1-6">{{ __('lang.relation_key') }}</label>
                                <input type="text" id="form-repeater-1-6" class="form-control relation_key"
                                    placeholder="{{ __('lang.relation_key') }}" name="relation_key" disabled />
                            </div>
                        </div>
                        <hr>
                        <h5>{{ __('lang.spacial') }}</h5>
                        <div class="row">
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="form-repeater-1-9">{{ __('lang.searchable') }}</label>
                                <select id="form-repeater-1-9" name="searchable" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="form-repeater-1-10">{{ __('lang.translatable') }}</label>
                                <select id="form-repeater-1-10" name="translatable" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                        </div>
                        <h5>{{ __('lang.validation') }}</h5>
                        <div class="row">
                            <div class="mb-3 col-lg-12 mb-0">
                                <label class="form-label" for="form-repeater-1-11">{{ __('lang.blade_type') }}</label>
                                <select id="form-repeater-1-11" name="blade_type" class="blade_type validationSelector form-select">
                                    <option value="" data-validation="">{{ __('lang.blade_type') }}</option>
                                    @foreach ($inputTypes as $type => $validations)
                                        <option value="{{ $type }}" data-validation="{{ json_encode($validations) }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                <div class="validations my-2">
                                   <div class="checkboxes mb-4"></div>
                                   <div class="mustInputs">
                                        <div class="row"></div>
                                   </div>
                                </div>
                            </div>



                            <div class="mb-3 col-lg d-flex align-items-center mb-0">
                                <button class="btn w-100 btn-label-danger mt-4" type="button" data-repeater-delete>
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
                <button type="submit" class="btn btn-primary waves-effect waves-light SubmitQuickBuilderForm">{{ __('lang.save') }}</button>
                <input type="hidden" name="validations" id="ValidationsData">
            </div>
        </div>
    </form>
    @push('scripts')
        <script src="{{ asset('dashboard/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
        <script src="{{ asset('dashboard/js/forms-extras.js') }}"></script>
        <script>

        </script>
        <script src="{{ asset('dashboard/js/quick-maker/quick-maker.js') }}"></script>
    @endpush
@endsection
 --}}
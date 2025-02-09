@extends('layouts.admin')
@section('content')
    <form action="{{ route('admin.quick-makers.store') }}" class="QuickBuilderForm" method="POST">
        <div class="card">
            <div class="card-body">
                <h2>{{ __('lang.new_section') }}</h2>
                <div class="row">
                    <div class="col-lg">
                        <label for="className" class="mb-2" class="form-label">{{ __('lang.name') }}</label>
                        <input type="text" class="form-control" name="name" id="className"
                            placeholder="{{ __('lang.name') }}">
                    </div>
                    <div class="col-12">
                        <br>
                        <div class="">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="form-label" for="form-4">{{ __('lang.is_module_child') }}</label>
                                    <select name="is_module_child" class=" form-select" id="is_module_child">
                                        <option value="0">{{ __('lang.no') }}</option>
                                        <option value="1">{{ __('lang.yes') }}</option>
                                    </select>
                                </div>
    
                                <div class="col-lg-6">
                                    <label class="form-label" for="module">{{ __('lang.module') }}</label>
                                    <select disabled  name="module" class="form-select" id="module">
                                        <option value="">
                                            {{ __('lang.no') }}
                                        </option>
                                        @foreach ($modules as $name => $module)
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>     
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="alert alert-danger errorAlert" role="alert"></div>
        <br>
        <div class="card">
            <div class="card-body">
                <h3 class="card-header">{{ __('lang.fields') }}</h3>
                <div class="row">
                    <div class="mb-3 col-lg mb-0 col-md-6">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#DatabaseModal"
                            class="btn btn-primary w-100">
                            {{ __('lang.database') }}
                        </button>
                    </div>
                    <div class="mb-3 col-lg mb-0 col-md-6">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#spacialModal"
                            class="btn btn-primary w-100">
                            {{ __('lang.spacial') }}
                        </button>
                    </div>
                    <div class="mb-3 col-lg mb-0 col-md-6">
                        <button 
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#relationshipModal"
                        class="btn btn-primary w-100">
                            {{ __('lang.relationship') }}
                        </button>
                    </div>
                    <div class="mb-3 col-lg mb-0 col-md-6">
                        <button 
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#validationlModal"
                        class="btn btn-primary w-100">
                            {{ __('lang.validation') }}
                        </button>
                    </div>
                    <div class="mb-3 col-lg mb-0 col-md-6">
                        <button type="button" class="btn btn-success w-100 AddColumn">{{ __('lang.add') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row ListGroupContainer">
            {{-- <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              Soufflé pastry pie ice
                              <span class="badge bg-primary">5</span>
                            </li>
                            <li class="list-group-item disabled">Bear claw cake biscuit</li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              Tart tiramisu cake
                              <span class="badge bg-success">2</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              Bonbon toffee muffin
                              <span class="badge bg-danger rounded-pill">3</span>
                            </li>
                            <li class="list-group-item">Dragée tootsie roll</li>
                          </ul>
                    </div>
                </div>
            </div> --}}
        </div>
        <br>
        @csrf
        <button type="button" class="btn btn-primary SendToDatabase">{{ __('lang.save') }}</button>
        <input name="formData" id="formData" type="hidden">
    </form>
    
    <div class="modal fade relative" id="spacialModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-2">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="py-0 rounded-top">
                        <h2 class=" mb-4">{{ __('lang.spacial') }}</h2>

                        <div class="row">
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="searchable">{{ __('lang.searchable') }}</label>
                                <select id="searchable" name="searchable" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="translatable">{{ __('lang.translatable') }}</label>
                                <select id="translatable" name="translatable" class="form-select">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="relationshipModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-2">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="py-0 rounded-top">
                        <h2 class=" mb-4">{{ __('lang.relationship') }}</h2>

                        <div class="row">
                            <div class="mb-3 col-lg mb-0">
                                <label class="form-label" for="relation">{{ __('lang.relation') }}</label>
                                <select id="relation" name="relation" class="form-select relationFinder">
                                    <option value="0">{{ __('lang.no') }}</option>
                                    <option value="1">{{ __('lang.yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg-3 mb-0">
                                <label class="form-label"
                                for="relation_model">{{ __('lang.relation_model') }}</label>
                                <select disabled id="relation_model" name="relation_model" class="form-select relation_model">
                                    @foreach ($models as $model)
                                        <option value="{{ $model }}">{{ $model }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-lg-3 mb-0">
                                <label class="form-label"
                                    for="relation_key">{{ __('lang.relation_key') }}</label>
                                <input type="text" id="relation_key" class="form-control relation_key"
                                    placeholder="{{ __('lang.relation_key') }}" name="relation_key" disabled />
                            </div>
                            <div class="mb-3 col-lg-3 mb-0">
                                <label class="form-label"
                                    for="relation_display">{{ __('lang.relation_display') }}</label>
                                <input type="text" id="relation_display" class="form-control relation_display"
                                    placeholder="{{ __('lang.relation_display') }}" name="relation_display" disabled />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="validationlModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-2">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="py-0 rounded-top">
                        <h2 class=" mb-4">{{ __('lang.spacial') }}</h2>

                        <div class="row">
                            <div class="mb-3 col-lg-12 mb-0">
                                <label class="form-label" for="blade_type">{{ __('lang.blade_type') }}</label>
                                <select id="blade_type" name="blade_type" class="blade_type validationSelector form-select">
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
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DatabaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-2">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="py-0 rounded-top">
                        <h2 class=" mb-4">{{ __('lang.database') }}</h2>

                        <div class="row">
                            <div class="mb-3 col-lg col-md-6 mb-0">
                                <label class="form-label" for="name">{{ __('lang.name') }}</label>
                                <input type="text" id="DatabaseName" class="form-control identifier"
                                    placeholder="{{ __('lang.name') }}" name="name" />
                            </div>
                            <div class="mb-3 col-lg mb-0 col-md-6">
                                <label class="form-label" for="value">{{ __('lang.value') }}</label>
                                <input type="text" id="DatabaseValue" class="form-control"
                                    placeholder="{{ __('lang.value') }}" name="value" />
                            </div>
                            <div class="mb-3 col-lg mb-0 col-md-6">
                                <label class="form-label" for="type">{{ __('lang.type') }}</label>
                                <select id="DatabaseType" name="type" class="form-select">
                                    @foreach ($types as $type)
                                        <option value="{{ $type->type }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('dashboard/js/quick-maker/quick-maker.js') }}"></script>
    @endpush
    

@endsection

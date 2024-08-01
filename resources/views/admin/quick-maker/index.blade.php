@extends('layouts.admin')
@section('content')
    <form action="{{ route('admin.quick-makers.create-module') }}" class="QuickBuilderForm" method="POST">
        <div class="card">
            <div class="card-body">
                <h2>{{ __('lang.new_module') }}</h2>
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="tyoe">{{ __('lang.name') }}</label>
                    <input name="name" class="form-control" />
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-primary SendToDatabase">{{ __('lang.save') }}</button>
                </div>
            </div>
        </div>
    </form>

   
   
    @push('scripts')
        <script src="{{ asset('dashboard/js/quick-maker/quick-maker.js') }}"></script>
    @endpush
@endsection

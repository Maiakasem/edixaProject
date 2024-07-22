@extends('layouts.admin')
@section('content')
    <div class="col-12 p-3">
        <h4 class="py-3 mb-4">{{ __('lang.users') }}</h4>
        <div class="d-flex justify-content-between">
            <div>
                @can('roles-create')
                    <a href="{{ route('admin.users.create') }}">
                        <span class="btn btn-primary"><span class="fas fa-plus"></span> {{ __('lang.add_new') }}</span>
                    </a>
                @endcan
            </div>
            <form method="GET">
                <input type="text" name="q" class="form-control" placeholder="{{ __('lang.search') }}"
                    value="{{ request()->get('q') }}">
            </form>
        </div>
        <br>
        <div>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered  table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('lang.active') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>

                                    @if (auth()->user()->can('traffics-read'))
                                        <td><a
                                                href="{{ route('admin.traffics.logs', ['user_id' => $user->id]) }}">{{ $user->logs_count }}</a>
                                        </td>
                                    @endif


                                    <td>
                                        @foreach ($user->roles as $role)
                                            {{ $role->display_name }}
                                            <br>
                                        @endforeach
                                    </td>


                                    <td>
                                        @can('users-read')
                                            <a href="{{ route('admin.users.show', $user) }}">
                                                <span class="btn  btn-outline-primary btn-sm font-small mx-1">
                                                    <span class="fas fa-search "></span> {{ __('lang.show') }}
                                                </span>
                                            </a>
                                        @endcan

                                        @can('users-update')
                                            <a href="{{ route('admin.users.edit', $user) }}">
                                                <span class="btn  btn-outline-success btn-sm font-small mx-1">
                                                    <span class="fas fa-wrench "></span> {{ __('lang.control') }}
                                                </span>
                                            </a>
                                        @endcan
                                        @can('users-delete')
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                class="d-inline-block">@csrf @method('DELETE')
                                                <button class="btn  btn-outline-danger btn-sm font-small mx-1"
                                                    onclick="var result = confirm('{{ __('lang.are_you_sure_for_delete') }} ؟');if(result){}else{event.preventDefault()}">
                                                    <span class="fas fa-trash "></span> {{ __('lang.delete') }}
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            {{ $users->appends(request()->query())->render() }}
        </div>
    @endsection

@extends('layouts.app', [
    'title' => __('User Management'),
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">{{ __('User Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
        @include('users.layouts.cards')
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Users') }}</h3>
                                <p class="text-sm mb-0">
                                        {{ __('This is an example of user management. This is a minimal setup in order to get started fast.') }}
                                    </p>
                            </div>
                            <!-- @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                    <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">{{ __('Add user') }}</a>
                                </div>
                            @endcan -->
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Photo</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Lastname') }}</th>
                                    <th scope="col">{{ __('Mobile') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Student Id') }}</th>
                                    <th scope="col">{{ __('Role') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    @can('manage-users', App\Model\User::class)
                                        <th scope="col"></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <span class="avatar avatar-sm rounded-circle">
                                            @if(auth()->user()->image != null)
                                                <img src="{{ asset('profile_user') }}/{{ auth()->user()->image->original_name }}" alt="{{ $user->firstname }}" style="max-width: 100px; border-radiu: 25px">
                                            @else
                                            <img src="" alt="{{ $user->firstname }}" style="max-width: 100px; border-radiu: 25px">
                                            @endif
                                            </span>
                                        </td>
                                        <td>{{ $user->firstname }}</td>
                                        <td>{{ $user->lastname }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                        </td>
                                        <td>
                                        @foreach($user->role as $role)
                                            {{$role->name}}
                                        @endforeach
                                        </td>
                                        <td>
                                        @if($user->statusAccount->completed == 1)
                                            {{ __('Active') }}
                                        @else
                                            {{ __('Inactive') }}
                                        @endif
                                        </td>
                                        <td>{{ date_format($user->created_at, 'Y-m-d' ) }}</td>
					                    @can('manage-users', App\Model\User::class)
					                        <td class="text-right">
                                                @if ($user->id != 1 && auth()->user()->can('update', $user) || auth()->user()->can('delete', $user))
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                @can('update', $user)
                                                                    <a class="dropdown-item" href="{{ route('user.edit', $user) }}">{{ __('Edit') }}</a>
                                                                @endcan
    							                                @can('delete', $user)
        							                                <form action="{{ route('user.destroy', $user) }}" method="post">
                                                                        @csrf
                                                                        @method('delete')

                                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                                            {{ __('Delete') }}
                                                                        </button>
                                                                    </form>
    						                                    @endcan

                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
					                    @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
@endpush

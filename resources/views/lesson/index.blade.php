@extends('layouts.app', [
    'title' => __('Lesson Management'),
    'parentSection' => 'laravel',
    'elementName' => 'lessons-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('lessons.index') }}">{{ __('Lessons Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Lessons') }}</h3>
                            </div>
                                <div class="col-4 text-right">
                                    <a href="{{ route('lessons.create') }}" class="btn btn-sm btn-primary">{{ __('Add Lesson') }}</a>
                                </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic31">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Assigned Topic') }}</th>
                                    <th scope="col">{{ __('Assigned Type') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lessons as $lesson)
                                    <tr>
                                        <td><?= ($lesson->status == 1) ? 'Published' : 'Unpublished'; ?></td>
                                        <td><a href="{{ route('lessons.edit', $lesson) }}">{{ $lesson->title }}</a></td>
                                        <td>
                                        @foreach($lesson->topic as $topic)
                                            {{ $topic->title }}
                                        @endforeach
                                        </td>
                                        <td>
                                        @foreach($lesson->type as $type)
                                            {{ $type->name }}
                                        @endforeach
                                        </td>

                                        <td class="text-right">

                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('lessons.edit', $lesson) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('lessons.destroy', $lesson) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>


                                                </div>
                                            </div>
                                        </td>

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
    <script>
         var table = $('#datatable-basic31').DataTable({
        language: {
            paginate: {
            next: '&#187;', // or '→'
            previous: '&#171;' // or '←'
            }
        }
    });
    </script>
@endpush

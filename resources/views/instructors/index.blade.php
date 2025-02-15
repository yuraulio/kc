@extends('layouts.app', [
    'title' => __('Instructor Management'),
    'parentSection' => 'laravel',
    'elementName' => 'instructors-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot
            @slot('filter')
                <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">{{ __('Filters') }}</a>

            @endslot

            <li class="breadcrumb-item"><a href="{{ route('instructors.index') }}">{{ __('Insctructors Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
        @include('instructors.layouts.cards')
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Instructors') }}</h3>
                            </div>
                            @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                    <a href="{{ route('instructors.create') }}" class="btn btn-sm btn-primary">{{ __('Add instructor') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="collapse" id="collapseExample">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-3 filter_col" id="filter_col" data-column="1">
                                    <label>Status</label>
                                    <select data-toggle="select" placeholder="Active" name="Name" class="column_filter" id="col1_filter" >
                                        <option value = ''> -- All -- </option>
                                        <option value = 'Active' selected> Active </option>
                                        <option value = 'Inactive'> Inactive </option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic30">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Firstname') }}</th>
                                    <th scope="col">{{ __('Lastname') }}</th>
                                    <th scope="col">{{ __('Created at') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($instructors as $instructor)
                                <?php //dd($instructor); ?>
                                    <tr>
                                        <td><span class="avatar avatar-sm rounded-circle">
                                        @if($instructor->medias != null && $instructor->medias['name'] != '')
                                            <img src="{{$instructor->medias['path']}}{{$instructor->medias['name']}}-users{{$instructor->medias['ext']}}"
                                            class="rounded-circle" alt="{{$instructor['title']}} {{$instructor['subtitle']}}">
                                        @endif
                                         </span> </td>
                                        <td><?= ($instructor->status == 0) ? 'Inactive' : 'Active';  ?></td>
                                        <td><a href="{{ route('instructors.edit', $instructor) }}">{{ $instructor->title }}</a></td>
                                        <td>{{ $instructor->subtitle }}</td>
                                        <td>{{ date_format($instructor->created_at, 'Y-m-d' ) }}</td>

                                        <td class="text-right">

                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('instructors.edit', $instructor) }}">{{ __('Edit') }}</a>

                                                    <form action="{{ route('instructors.destroy', $instructor) }}" method="post">
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
        // DataTables initialisation
        var table = $('#datatable-basic30').DataTable({
            order: [[4, 'desc']],
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        $( document ).ready(function(){
            $('#col1_filter').select2({
                minimumResultsForSearch: -1
            })
        });

        $('select.column_filter').on('change', function () {
            filterColumn( $(this).parents('div').attr('data-column') );
        });

        function filterColumn ( i ) {

            $('#datatable-basic30').DataTable().column( i ).search(
                $('#col'+i+'_filter').val()
            ).draw();


        }
    </script>
@endpush

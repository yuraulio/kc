@extends('layouts.app', [
    'title' => __('Pages Management'),
    'parentSection' => 'laravel',
    'elementName' => 'pages-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot
            @slot('filter')
                <!-- <a href="#" class="btn btn-sm btn-neutral">{{ __('Filters') }}</a> -->
                <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">{{ __('Filters') }}</a>

            @endslot
            <li class="breadcrumb-item"><a href="{{ route('notification.show') }}">{{ __('Pages') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
        @include('pages.layouts.cards')
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Pages') }}</h3>
                            </div>
                            @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                    <a href="{{ route('pages.create') }}" class="btn btn-sm btn-primary">{{ __('Add page') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="collapse" id="collapseExample">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-3 filter_col" id="filter_col1" data-column="1">
                                    <label>Status</label>
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                    <option selected value> -- All -- </option>
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
                        <table class="table table-flush"  id="datatable-basic34">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Created') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pages as $page)
                                    <tr>
                                        <td><a href="{{ route('pages.edit', $page) }}">{{ $page->name }}</a></td>
                                        <td>{{ $page->published ? 'Published' : 'Unpublished' }}</td>
                                        <td>{{ $page->created_at->format('d/m/Y H:i') }}</td>


                                        @can('manage-users', App\Model\User::class)
					                        <td class="text-right">
                                            <?php //dd($user); ?>
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    @if ($user->id != auth()->id())
                                                        @can('update', $user)
                                                            <a class="dropdown-item" href="{{ route('pages.edit', $page) }}">{{ __('Edit') }}</a>
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
                                                    @else
                                                        <a class="dropdown-item" href="{{ route('pages.edit', $page) }}">{{ __('Edit') }}</a>
                                                    @endif
                                                </div>
                                            </div>

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

    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">

    // DataTables initialisation
    var table = $('#datatable-basic34').DataTable({
                language: {
                    paginate: {
                    next: '&#187;', // or '→'
                    previous: '&#171;' // or '←'
                    }
                }
            });
    </script>


    <script>
        $( document ).ready(function(){
            
            let published = table.column(1).data().unique();
            
            $.each(published, function(key, value){

                $('#col1_filter').append(`<option value="${value}">${value}</option>`)

            })

            $('select.column_filter').on('change', function () {
            filterColumn( $(this).parents('div').attr('data-column') );
        });

        function filterColumn ( i ) {   
            
            if($('#col'+i+'_filter').val() && i != 8){
                $('#datatable-basic34').DataTable().column( i ).search(
                    '^'+$('#col'+i+'_filter').val()+'$', true,true
                ).draw();
            }else{
                $('#datatable-basic34').DataTable().column( i ).search(
                    $('#col'+i+'_filter').val()
                ).draw();
            }
            
        }

            
        });
    </script>

@endpush

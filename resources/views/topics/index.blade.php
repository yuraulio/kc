@extends('layouts.app', [
    'title' => __('Topics Management'),
    'parentSection' => 'laravel',
    'elementName' => 'topics-management'
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

            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">{{ __('Topics Management') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Topics') }}</h3>
                            </div>
                            @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                    <a href="{{ route('topics.create') }}" class="btn btn-sm btn-primary">{{ __('Add Topic') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="collapse" id="collapseExample">
                        <div class="container">
                            <div class="row">

                                <div class="col-sm-4 filter_col" id="filter_col1" data-column="1">
                                    <label>Category</label>
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                    <option selected value=""> -- All -- </option>
                                    </select>
                                </div>



                            </div>
                        </div>
                    </div>

                    <!-- <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Assigned Category') }}</th>
                                    <th scope="col">{{ __('Created at') }}</th>
                                    @can('manage-users', App\Model\User::class)
                                        <th scope="col"></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topics as $topic)
                                    <tr>
                                        <td>{{ $topic->status }}</td>
                                        <td> <a href="{{ route('topics.edit', $topic) }}">{{ $topic->title }}</a></td>
                                        <td>
                                        @foreach($topic['category'] as $category)
                                            {{$category->name}}
                                        @endforeach
                                        </td>
                                        <td>{{ $topic->created_at ? date_format($topic->created_at, 'Y-m-d' ) : '' }}</td>

					                        <td class="text-right">

                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">


                                                                <a class="dropdown-item" href="{{ route('topics.edit', $topic) }}">{{ __('Edit') }}</a>

        							                            <form action="{{ route('topics.destroy', $topic) }}" method="post">
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
                    </div> -->

                    <div class="accordion" id="accordionTopicMain">

                        @foreach($categories as $category)

                        <div id="{{$category['id']}}" class="card">
                            <div class="card-header" id="catt_{{$category['id']}}" data-toggle="collapse" data-target="#cat_{{$category['id']}}" aria-expanded="false" aria-controls="collapseOne">
                                <h5 class="mb-0">{{$category->name}}</h5>
                            </div>
                            <div id="cat_{{$category['id']}}" class="collapse" aria-labelledby="cat1_{{$category['id']}}" data-parent="#accordionTopicMain">
                                <div class="card-body">


                                <div class="table-responsive py-4">
                                        <table class="table align-items-center table-flush datatable-basic39">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">{{ __('Status') }}</th>
                                                    <th scope="col">{{ __('Title') }}</th>
                                                    <th scope="col">{{ __('Assigned Category') }}</th>
                                                    <th scope="col">{{ __('Created at') }}</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($category->topics as $topic)
                                                    <tr>
                                                        <td><?= ($topic->status == 1) ? 'Published' : 'Unpublished'; ?></td>
                                                        <td> <a href="{{ route('topics.edit', $topic) }}">{{ $topic->title }}</a></td>
                                                        <td>
                                                        @foreach($topic['category'] as $category)
                                                            {{$category->name}}
                                                        @endforeach
                                                        </td>
                                                        <td>{{ $topic->created_at ? date_format($topic->created_at, 'Y-m-d' ) : '' }}</td>

                                                            <td class="text-right">

                                                                <div class="dropdown">
                                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">


                                                                                <a class="dropdown-item" href="{{ route('topics.edit', $topic) }}">{{ __('Edit') }}</a>

                                                                                <form action="{{ route('topics.destroy', $topic) }}" method="post">
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

                        @endforeach

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

    <script type="text/javascript">
        let categories = @json($categories);
        var table = $('.datatable-basic39').DataTable({
                language: {
                    paginate: {
                    next: '&#187;', // or '→'
                    previous: '&#171;' // or '←'
                    }
                }
            });


        $(function() {
            $("#col1_filter").change(function() {
                let selectedCategoryId = $(this).val()

                let topics = $('#accordionTopicMain .card')

                if(selectedCategoryId != ''){
                    $.each(topics, function(key, value) {
                        let id = $(value).attr('id')
                        if(id != selectedCategoryId){
                            $(value).addClass('d-none')
                        }else{
                            if($(value).hasClass('d-none')){
                                $(value).removeClass('d-none')
                            }
                        }

                    })
                }else{
                    $.each(topics, function(key, value) {
                        $(value).removeClass('d-none')
                    })
                }



            })

            $.each(categories, function(key, value) {
                let row = `
                    <option value="${value.id}">${value.name}</option>
                `
                $('#col1_filter').append(row)
            })
        });

    </script>
@endpush

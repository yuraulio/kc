@extends('layouts.app', [
    'title' => __('Faqs Management'),
    'parentSection' => 'laravel',
    'elementName' => 'faqs-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('faqs.index') }}">{{ __('Faqs Management') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Faqs') }}</h3>
                            </div>
                            @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                <form hidden id="submit-file" action="{{ route('faqs.file.import') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input id="file-input" name="file" type="file" class="btn btn-sm btn-primary"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" value="{{ __('Import Faqs From file') }}" style="display: none;">
                                </form>

                                <a id="download-sample" href="javascript:void(0)" class="btn btn-sm btn-primary">{{ __('Sample File') }}</a>
                                <a id="import-from-file" href="javascript:void(0)" class="btn btn-sm btn-primary">{{ __('Import Faqs From file') }}</a>
                                <a href="{{ route('faqs.create') }}" class="btn btn-sm btn-primary">{{ __('Add Faq') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic27">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Assigned Category') }}</th>
                                    <th scope="col">{{ __('Created at') }}</th>
                                    @can('manage-users', App\Model\User::class)
                                        <th scope="col"></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($faqs as $faq)
                                    <tr>
                                        <td><a href="{{ route('faqs.edit', $faq) }}">{{ $faq->title }}</a></td>
                                        <td>
                                        @foreach($faq->category as $category)
                                            {{$category->name}}
                                        @endforeach
                                        </td>
                                        <td>{{ date_format($faq->created_at, 'Y-m-d' ) }}</td>
					                        <td class="text-right">

                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" href="{{ route('faqs.edit', $faq) }}">{{ __('Edit') }}</a>
                                                        <form action="{{ route('faqs.destroy', $faq) }}" method="post">
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
        var table = $('#datatable-basic27').DataTable({
                    language: {
                        paginate: {
                        next: '&#187;', // or '→'
                        previous: '&#171;' // or '←'
                        }
                    }
                });
        </script>



<script>
    $(document).ready(() => {
        $('#import-from-file').on('click', function (event) {
            event.preventDefault();
            $("#file-input").trigger('click');
        });

        $('#download-sample').on('click', function (event) {
            event.preventDefault();
            window.location.href = '/import/Faqs_sample.xlsx';
        });

    });

    $("#file-input").change(function(){
        $("#submit-file").submit();
    })

</script>
@endpush

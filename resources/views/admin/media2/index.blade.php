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

            <li class="breadcrumb-item"><a href="{{ route('media2.index') }}">{{ __('Media Management') }}</a></li>
        @endcomponent
    @endcomponent

    <div id="file_manager" class="container-fluid mt--6">
        <div class="row">
            <div class="col">
            <div style="height: 600px;">
                <div id="fm"></div>
            </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
    <ul id="context-menu">
    <li>Item 1</li>
    <li>Item 2</li>
</ul>
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



    $( document ).ready(function() {

        //console.log( "ready!" );
        search = `<div role="group" class="btn-group"><div class="p-4 bg-secondary">
                        <input type="text" class="form-control form-control-alternative search_input" placeholder="Search">
                        <button class="btn btn-primary btn-sm" id="search_btn" type="button">Button</button>
                    </div></div>`
        $('#file_manager .fm-navbar .col-auto.text-right').append(search)
    });



    $( document ).on("click","#search_btn",function() {
        file = $('.search_input').val()
        //console.log(file)
        $.ajax({
                type: 'GET',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/file-manager/search?param="+file,
                success: function(data) {
                    // data = JSON.parse(data)
                    data = data.data

                    first_grid = $('.fas.fa-th-list').parent().hasClass('active')

                    if(first_grid){
                        $('.fm-content-body tbody').empty()
                    }else{
                        $('.fm-content-body .d-flex.align-content-start.flex-wrap').empty()
                    }

                    $.each(data, function(key, value) {
                        console.log('test'+value[key])
                        if(first_grid){
                            row = `<tr class="search-result"><td class="fm-content-item unselectable"><i class="far fa-file"></i> ${value.name} </td><td></td><td>Folder</td><td> 6/7/2021, 12:13:38 PM </td></tr>`
                            $('.fm-content-body tbody').append(row)
                        }else{
                            row =`<div title="fdf" class="fm-grid-item text-center unselectable active"><div class="fm-item-icon"><i class="fa-5x pb-2 far fa-folder"></i></div><div class="fm-item-info">${value.name}</div></div>`
                            $('.fm-content-body .d-flex.align-content-start.flex-wrap').appen(row)
                        }


                    })


                }
            });
    });
    $(document).on("click", ".table.table-sm tr", function(){
        $(this).removeClass('table-info')
        $(this).addClass('table-info')


    })




    </script>
@endpush

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

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content fm-modal-preview">
            <div class="modal-header">
            <h5 class="modal-title w-75 text-truncate"> Preview <small class="text-muted pl-3 title-small"></small></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <img src="" alt="" style="max-height: 300px;">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content fm-modal-delete">
            <div class="modal-header">
            <h5 class="modal-title w-75 text-truncate"> Delete <small class="text-muted pl-3 title-small"></small></h5>

                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div>
                    <div class="fm-additions-file-list">

                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button id="deleteFiles" class="btn btn-danger">Delete </button>
                <button class="btn btn-light">Cancel</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content fm-modal-delete">
            <div class="modal-header">
            <h5 class="modal-title w-75 text-truncate"> Delete <small class="text-muted pl-3 title-small"></small></h5>

                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="fm-input-rename">Enter new name</label>
                    <input type="text" id="fm-input-rename" class="form-control">
                    <div class="invalid-feedback" style="display: none;"> Invalid name </div>
                </div>
            </div>


            <div class="modal-footer">
                <button disabled="disabled" class="btn btn-info">Submit </button>
                <button class="btn btn-light">
            </div>
            </div>
        </div>
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
        $(function(){
            $('.fm-content-body tbody').contextMenu({
                selector: 'tr',
                build: function($trigger, e) {
                    // this callback is executed every time the menu is to be shown
                    // its results are destroyed every time the menu is hidden
                    // e is the original contextmenu event, containing e.pageX and e.pageY (amongst other data)
                    return {
                        callback: function(key, options) {
                            var base_url = window.location.protocol + "//" + window.location.host
                            name = $(this).find('td').text()
                            path = $(this).children().data('path')

                            if(key == 'download'){
                                const a = document.createElement('a');
                                a.style.display = 'none';
                                a.href = base_url+'/uploads'+path;
                                // the filename you want
                                a.download = name;
                                document.body.appendChild(a);
                                a.click();
                                window.URL.revokeObjectURL(base_url+'/uploads'+path);
                            }else if(key == 'view'){
                                $('#previewModal').modal('toggle');
                                $('#previewModal .title-small').text(name)
                                $('#previewModal img').attr('src', base_url+'/uploads'+path)

                            }else if(key == 'delete'){
                                $('#deleteModal .fm-additions-file-list').empty()
                                $('#deleteModal').modal('toggle');
                                let selected_elem = $('.table-info')

                                $.each(selected_elem, function(key1, value) {
                                    path = $(value).find('td').data('path')
                                    name = $(value).find('td').data('name')

                                    row = ` <div data-path="${path}" class="d-flex justify-content-between selected-files">
                                                <div class="w-75 text-truncate"><span><i class="far fa-file-image"></i> ${name} </span></div>
                                                <div class="text-right"></div>
                                            </div>`

                                    $('#deleteModal .fm-additions-file-list').append(row)

                                });

                            }else if(key == 'rename'){
                                $('#renameModal').modal('toggle');
                            }


                            // var m = "clicked: " + key + " on " + $(this).text();
                            // window.console && console.log(m) || alert(m);
                        },
                        items: {
                            "view": {name: "View", icon: "fas fa-eye"},
                            "download": {name: "Download", icon: "fas fa-download"},

                            "rename": {name: "Rename", icon: "fas fa-edit"},
                            "sep1": "---------",
                            "delete": {name: "Delete", icon: "delete"},
                            }
                    }
                }
            });
        });
    </script>

    <script>

    $( document ).on("click","#deleteFiles",function() {
        selected_elem = $('.selected-files')

        let items = []
        items['items'] = []

        $.each(selected_elem, function(key, value) {

            items['items'][key] = []
            items['items'][key]= { path: $(value).data('path'), type: 'file' }
        })


        data = {disk: 'uploads', items: items['items']}

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            url: "/file-manager/delete",
            data: JSON.stringify(data),
            success: function(data) {
                // data = JSON.parse(data)
                if(data){
                    $('#deleteModal').modal('hide');
                    $('.table-info').remove()
                }
            }
        });

    });


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
                        console.log(value.path+'/'+value.name)
                        if(first_grid){
                            row = `<tr class="search-result"><td data-name="${value.name}" data-path='${value.path}/${value.name}' class="fm-content-item unselectable fm-download"><i class="far fa-file"></i> ${value.name} </td><td></td><td>File</td><td> 6/7/2021, 12:13:38 PM </td></tr>`
                            $('.fm-content-body tbody').append(row)
                        }else{
                            row =`<div title="fdf" class="fm-grid-item text-center unselectable active fm-download"><div class="fm-item-icon"><i class="fa-5x pb-2 far fa-folder"></i></div><div class="fm-item-info">${value.name}</div></div>`
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

    // $(document).on("click", ".fm-download", function(){




    // })

    /*

    // Trigger action when the contexmenu is about to be shown
$(document).bind("contextmenu", function (event) {

    // Avoid the real one
    event.preventDefault();

    // Show contextmenu
    $(".custom-menu").finish().toggle(100).

    // In the right position (the mouse)
    css({
        top: event.pageY + "px",
        left: event.pageX + "px"
    });
});


// If the document is clicked somewhere
$(document).bind("mousedown", function (e) {

    // If the clicked element is not the menu
    if (!$(e.target).parents(".custom-menu").length > 0) {

        // Hide it
        $(".custom-menu").hide(100);
    }
});


// If the menu element is clicked
$(".custom-menu li").click(function(){

    // This is the triggered action name
    switch($(this).attr("data-action")) {

        // A case for each action. Your actions here
        case "first": alert("first"); break;
        case "second": alert("second"); break;
        case "third": alert("third"); break;
    }

    // Hide it AFTER the action was triggered
    $(".custom-menu").hide(100);
  });

*/


    </script>


@endpush

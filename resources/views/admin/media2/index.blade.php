@extends('layouts.app', [
    'title' => __('Media Management'),
    'parentSection' => 'laravel',
    'elementName' => 'media-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('media2.index') }}">{{ __('Media Management') }}</a></li>
        @endcomponent
    @endcomponent

    <div id="file_manager" class="container-fluid mt--6">
        <div class="row">
            <div class="col">
            <div id="file-man-main" style="height: 600px;">
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
                    <img id="preview-img-file" src="" alt="" style="max-height: 300px;">

                    <div id="fm-additions-cropper" class="fm-additions-cropper">

                        <div class="row" style="max-height: 300px;">
                            <div class="col-sm-9 cropper-block">
                                <!-- <img src="" alt="" class="cropper-hidden cropper-img"> -->
                                <div class="cropper-container cropper-bg" touch-action="none" style="width: 745px; height: 300px;">
                                    <div class="cropper-wrap-box">
                                        <div class="cropper-canvas">
                                            <img id="img-cropper" src="" alt="" class="cropper-hide cropper-img">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-3 pl-0">
                                <div class="cropper-preview"></div>

                                    <input type="file" value="" name="image" class="image" id="upload_image" style="display:none" />

                                    <!-- <div class="preview" style="display: block; width: 270px; height: 116.971px; min-width: 0px !important; min-height: 0px !important; max-width: none !important; max-height: none !important;"></div> -->

                                    <!-- <img src="http://kcversion8.test/file-manager/preview?disk=uploads&amp;path=company32.png&amp;v=1625661829" alt="company32.png" style="display: block; width: 270px; height: 116.971px; min-width: 0px !important; min-height: 0px !important; max-width: none !important; max-height: none !important; transform: translateX(-27px) translateY(-11.6971px);"></div> -->
                                    <div class="cropper-data">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-prepend"><label for="dataX" class="input-group-text">X</label></span>
                                            <input type="text" value="" id="dataX" class="form-control">
                                            <span class="input-group-append">
                                                <span class="input-group-text">px</span>
                                            </span>
                                        </div>
                                        <div class="input-group input-group-sm"><span class="input-group-prepend"><label for="dataY" class="input-group-text">Y</label></span>
                                            <input type="text" value="" id="dataY" class="form-control">
                                            <span class="input-group-append">
                                                <span class="input-group-text">px</span>
                                            </span>
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-prepend"><label for="dataWidth" class="input-group-text">Width</label></span>
                                            <input type="text" value="" id="dataWidth" class="form-control">
                                            <span class="input-group-append">
                                                <span class="input-group-text">px</span>
                                            </span>
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-prepend"><label for="dataHeight" class="input-group-text">Height</label></span>
                                            <input type="text" value="" id="dataHeight" class="form-control">
                                            <span class="input-group-append"><span class="input-group-text">px</span></span>
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-prepend">
                                                <label for="dataRotate" class="input-group-text">Rotate</label>
                                            </span>
                                            <input type="text" value="" id="dataRotate" class="form-control">
                                            <span class="input-group-append">
                                                <span class="input-group-text">deg</span>
                                            </span>
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-prepend"><label for="dataScaleX" class="input-group-text">ScaleX</label></span>
                                            <input type="text" value="" id="dataScaleX" class="form-control">
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-prepend"><label for="dataScaleY" class="input-group-text">ScaleY</label></span>
                                            <input type="text" value="" id="dataScaleY" class="form-control">
                                        </div>
                                        <button id="apply-crop" title="Apply" type="button" class="btn btn-block btn-sm btn-info mb-2"><i class="fas fa-check"></i></button>
                                    </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>

                                <div role="group" aria-label="Scale" class="btn-group mr-2">
                                    <button id="move-left" class="btn btn-info"><i class="fas fa-arrow-left"></i></button>
                                    <button id="move-right" class="btn btn-info"><i class="fas fa-arrow-right"></i></button>
                                    <button id="move-up" class="btn btn-info"><i class="fas fa-arrow-up"></i></button>
                                    <button id="move-down" class="btn btn-info"><i class="fas fa-arrow-down"></i></button>
                                </div>
                                <div role="group" aria-label="Scale" class="btn-group mr-2">
                                    <button id="scale-X" class="btn btn-info"><i class="fas fa-arrows-alt-h"></i></button>
                                    <button id="scale-Y" class="btn btn-info"><i class="fas fa-arrows-alt-v"></i></button>
                                </div>
                                <div role="group" aria-label="Rotate" class="btn-group mr-2">
                                    <button id="rotate-plus" class="btn btn-info"><i class="fas fa-undo"></i></button>
                                    <button id="rotate-minus" class="btn btn-info"><i class="fas fa-redo"></i></button>
                                </div>
                                <div role="group" aria-label="Rotate" class="btn-group mr-2">
                                    <button id="zoom-plus" class="btn btn-info"><i class="fas fa-search-plus"></i></button>
                                    <button id="zoom-minus" class="btn btn-info"><i class="fas fa-search-minus"></i></button>
                                </div>

                                <button id="crop-reset" title="Reset" class="btn btn-info mr-2"><i class="fas fa-sync-alt"></i></button>
                                <button id="crop-img-modal" title="Save" class="btn btn-danger mr-2"><i class="far fa-save"></i></button>
                            </div>
                            <span class="d-block">
                                <button id="back-btn-preview" class="btn btn-light">Back</button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between pre-crop-modal">
                    <span class="d-block">
                        <button id="crop-image-file-manager" title="Cropping" class="btn btn-info"><i class="fas fa-crop-alt"></i></button>
                    </span>
                    <span class="d-block"><button class="btn btn-light close_modal">Cancel</button></span>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

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
                <button class="btn btn-light close_modal">Cancel</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content fm-modal-rename">
                <div class="modal-header">
                <h5 class="modal-title w-75 text-truncate"> Rename <small class="text-muted pl-3 title-small"></small></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="fm-input-rename">Enter new name</label>
                        <input type="text" id="fm-input-rename" value="" class="form-control">
                        <input type="hidden" id="old-input-rename" value="" class="form-control">
                        <input type="hidden" id="folder-ren" value="" class="form-control">
                        <div class="invalid-feedback" style="display: none;"> Invalid name </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button id="renameFile" disabled="disabled" class="btn btn-info">Submit </button>
                    <button class="btn btn-light close_modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clipboard Modal -->
    <div class="modal fade" id="clipboardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content fm-modal-clipboard">
                <div class="modal-header">
                    <h5 class="modal-title"> Clipboard </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <div class="w-75 text-truncate"><span><i class="far fa-hdd"></i>uploads </span></div>
                        <div class="text-right text-muted"><span title="Type - Copy"><i class="fas fa-copy"></i></span></div>
                    </div>
                    <hr>
                    <div id="clipboard-files">
                    </div>
                </div>


                <div class="modal-footer">
                    <button class="btn btn-danger clear-files">Clear </button>
                    <button class="btn btn-light close_modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Zip Modal -->
    <div class="modal fade" id="zipModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content fm-modal-zip">
                <div class="modal-header">
                    <h5 class="modal-title"> Create archive </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <label for="fm-zip-name">Archive name</label>
                    <div class="input-group mb-3">
                        <input type="text" value="" id="fm-zip-name" class="form-control">
                        <div class="input-group-append"><span class="input-group-text">.zip</span></div>
                        <div class="invalid-feedback" style="display: none;"> Archive exists! </div>
                    </div>
                    <hr>
                    <div class="fm-additions-file-list">

                    </div>
                </div>


                <div class="modal-footer">
                    <button id="zip-submit" disabled="disabled" class="btn btn-info">Submit </button>
                    <button class="btn btn-light close_modal">Cancel</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Properties Modal -->
    <div class="modal fade" id="propertiesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content fm-modal-properties">
                <div class="modal-header">
                    <h5 class="modal-title"> Properties </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>



                <div class="modal-body">
                <div class="row">
                        <div class="col-2">Alt Text:</div>
                        <div class="col-7">
                            <input type="text" id="image-alt" class="form-control" placeholder="Enter Alt Text" value="">
                            <input type="hidden" id="image-alt-id" class="form-control" value="">
                        </div>
                        <div class="col-2 save-alt-btn">
                            <button id="save-alt-btn1" class="btn btn-sm btn-light" type="button"><i class="fas fa-save"></i>Save</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">Disk:</div>
                        <div class="col-9">uploads</div>
                    </div>
                    <div class="row">
                        <div class="col-2">Name:</div>
                        <div id="properties-name" class="col-9 properties">companyAvatar-header-image.png</div>
                        <div class="col-1 text-right"><i title="Copy" class="far fa-copy"></i></div>
                    </div>
                    <div class="row">
                        <div class="col-2">Path:</div>
                        <div id="properties-path" class="col-9 properties">originals/companyAvatar-header-image.png</div>
                        <div class="col-1 text-right"><i title="Copy" class="far fa-copy"></i></div>
                    </div>
                    <div class="row">
                        <div class="col-2">Size:</div>
                        <div id="properties-size" class="col-9"></div>
                        <div class="col-1 text-right"><i title="Copy" class="far fa-copy"></i></div>
                    </div>

                    <div class="row">
                        <div class="col-2">Versions:</div>
                        <div id="properties-size" class="col-9">
                            <span>
                                <button id="version-btn" class="btn btn-sm btn-light" type="button"><i class=""></i>Enter</button>
                            </span>
                        </div>
                        <div class="col-1 text-right"><i title="Copy" class="far fa-copy"></i></div>
                    </div>

                    <div class="row">
                        <div class="col-2">Modified:</div>
                        <div id="properties-mod" class="col-9 properties"></div>
                    </div>
                </div>




                <div class="modal-footer">
                    <button class="btn btn-light close_modal">Cancel</button>
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
        let value = 200
        let selected = 0
        var cropBoxData;

        var canvasData;
        var cropper;

        $(document).ready(function() {

            $('#previewModal').on('hidden.bs.modal', function () {
                cropBoxData = cropper.getCropBoxData();
                canvasData = cropper.getCanvasData();
                cropper.destroy();
            });


            $( document ).on('click', '#move-left', function() {
                cropper.move(-15,0);
            })

            $( document ).on('click', '#move-right', function() {
                cropper.move(15,0);
            })

            $( document ).on('click', '#move-up', function() {
                cropper.move(0,-15);
            })

            $( document ).on('click', '#move-down', function() {
                cropper.move(0,15);
            })

            $( document ).on('click', '#rotate-plus', function() {
                cropper.rotate(-45);
            })

            $( document ).on('click', '#rotate-minus', function() {
                cropper.rotate(45);
            })

            $( document ).on('click', '#zoom-plus', function() {
                cropper.zoom(0.1);
            })

            $( document ).on('click', '#zoom-minus', function() {
                cropper.zoom(-0.1);
            })

            $( document ).on('click', '#crop-reset', function() {
                cropper.reset();
            })

            $( document ).on('click', '#scale-X', function() {
                let x = $('#dataScaleX').val()
                if(x == -1){
                    cropper.scaleX(1);
                }else{
                    cropper.scaleX(-1);
                }

            })

            $( document ).on('click', '#version-btn', function() {

                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/admin/media2_image",
                    data: {name: $('#properties-name').text()},
                    success: function(data) {

                        window.location = '/admin/media2/' + data.data;


                    }
                });
            })

            $( document ).on('click', '#save-alt-btn1', function() {
                let elem = $(this).parent().parent().find('input')
                let alt_text = $(elem).val()
                let image_name = $('#properties-name').text()
                let image_alt_id = $('#image-alt-id').val()

                if(image_alt_id == ''){
                    image_alt_id = 0;
                }

                image_name = image_name.split('.')[0]

                data = {id: image_alt_id, name: image_name, alt: alt_text}

                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/file-manager/saveAlt",
                    data: data,
                    success: function(data) {

                        $('#image-alt-id').val(data.data.id)


                    }
                });
            })



            $( document ).on('click', '#crop-img-modal', function(e) {
                var rest = path.substring(0, path.lastIndexOf("/") + 1);
                var last = path.substring(path.lastIndexOf("/") + 1, path.length);

                data = {folder: rest, name: last, x:cropper.getData({rounded: true}).x, y:cropper.getData({rounded: true}).y, width:cropper.getData({rounded: true}).width, height:cropper.getData({rounded: true}).height}
                //EDW
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/admin/media/crop_file_manager_image",
                    data: data,
                    success: function(data) {

                        $('#img-cropper').attr('src', data)
                        $('.cropper-crop-box.table-info img').attr('src', data)


                    }
                });
            })

            $( document ).on('click', '#move-left', function() {
                cropper.move(-15,0);
            })


            $( document ).on('click', '#apply-crop', function() {
                x = $('#dataX').val()
                y = $('#dataY').val()
                height = $('#dataHeight').val()
                width = $('#dataWidth').val()
                rotate = $('#dataRotate').val()
                scaleX = $('#dataScaleX').val()
                scaleY = $('#dataScaleY').val()

                cropper.setData({
                    x: parseInt(x),
                    y: parseInt(y),
                    height: parseInt(height),
                    width: parseInt(width),
                    rotate: parseInt(rotate),
                    scaleX: parseInt(scaleX),
                    scaleY: parseInt(scaleY),
                })



            })


        });


    </script>
    <script>

        let files = []
        let type = ''

        $(function(){
            $('.fm-content-body tbody').contextMenu({
                selector: 'tr.search-result',
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

                                $('.cropper-img').attr('src', base_url+'/uploads'+path)

                                $('#upload_image').val(base_url+'/uploads'+path)



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
                                $('#fm-input-rename').val('')
                                $('#old-input-rename').val('')
                                $('#folder-ren').val('')
                                let elem = $(this).find('td')[0]

                                name = $(elem).data('name')
                                str = $(elem).data('path')
                                //console.log($(elem).data('name'))

                                //console.log('test: '+$('#fm-input-rename').val())

                                // split last slash
                                var rest = str.substring(0, str.lastIndexOf("/") + 1);
                                var last = str.substring(str.lastIndexOf("/") + 1, str.length);

                                //console.log('last-----'+last)

                                $('#renameModal').modal('toggle');
                                $('#fm-input-rename').val(name)
                                $('#old-input-rename').val(name)
                                $('#folder-ren').val(rest)

                            }else if(key == 'copy'){
                                type = "copy"
                                elem_clip = $('.col-auto.text-right').find('span')[1]
                                $(elem_clip).attr('id', 'clipboard-toggle')

                                $(elem_clip).show()


                                let element_for_copy = $('.table-info')
                                $.each(element_for_copy, function(key, value) {
                                    local_elem = $(value).find('td')[0]
                                    path = $(local_elem).data('path')
                                    name = $(local_elem).data('name')

                                    files[key] = []
                                    files[key]['path'] = path
                                    files[key]['name'] = name

                                })
                                //console.log(files)


                                //console.log($elem_clip)
                            }else if(key == 'paste'){

                                path = ''
                                $.each( $('.fm-breadcrumb li'), function(key, value) {
                                    if(key != 0){
                                        path = path+'/'+$(value).text()
                                    }
                                })


                                obj1 = {}

                                $.each(files, function(key, value){
                                    //console.log(value)
                                    obj1[key] = value['path']
                                })

                                let arr = {};
                                arr = {[key]: key, type: type, disk: 'uploads', directories: [], files: obj1}

                                //console.log(path)

                                data = { disk: 'uploads', path: path, clipboard: arr}


                                $.ajax({
                                    type: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                    },
                                    contentType: "application/json; charset=utf-8",
                                    dataType: "json",
                                    url: "/file-manager/paste",
                                    data: JSON.stringify(data),
                                    success: function(data) {
                                        //console.log('data: '+data.new)

                                    }
                                });


                            }else if(key == 'cut'){
                                type = "cut"
                                elem_clip = $('.col-auto.text-right').find('span')[1]
                                $(elem_clip).attr('id', 'clipboard-toggle')

                                $(elem_clip).show()


                                let element_for_copy = $('.table-info')
                                $.each(element_for_copy, function(key, value) {
                                    local_elem = $(value).find('td')[0]
                                    path = $(local_elem).data('path')
                                    name = $(local_elem).data('name')

                                    files[key] = []
                                    files[key]['path'] = path
                                    files[key]['name'] = name

                                })

                            }else if(key == 'zip'){
                                files = []
                                $('#zipModal').modal('toggle');

                                selected_elem = $('.table-info')

                                $.each(selected_elem, function(key,value) {
                                    elem = $(value).find('td')[0]
                                    path = $(elem).data('path')
                                    name = $(elem).data('name')

                                    row = `
                                        <div class="d-flex justify-content-between">
                                            <div class="w-75 text-truncate"><span><i class="far fa-file-image"></i> ${name} </span></div>
                                        </div>
                                    `

                                    $('.fm-additions-file-list').append(row)


                                    files[key] = []
                                    files[key]['path'] = path
                                    files[key]['name'] = name


                                })

                            }else if(key == 'properties'){
                                $('#image-alt-id').val('0')
                                $('#image-alt').val('')
                                $('#propertiesModal').modal('show');
                                details = name.split(' ')
                                $('#properties-name').text(details[1])
                                $('#properties-path').text(path)
                                $('#properties-size').text(details[2]+' KB')
                                $('#properties-mod').text(details[4])
                                //console.log('name: '+name + 'path:  '+ path)

                                let name1 = details[1].split('.')
                                data = { media_name: name1[0]}

                                $.ajax({
                                    type: 'GET',
                                    headers: {
                                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                    },
                                    contentType: "application/json; charset=utf-8",
                                    dataType: "json",
                                    url: "/file-manager/fetchAlt",
                                    data: data,
                                    success: function(data) {
                                        $('#image-alt-id').val(data.data.id)
                                        $('#image-alt').val(data.data.alt)

                                    }
                                });
                            }


                            // var m = "clicked: " + key + " on " + $(this).text();
                            // window.console && console.log(m) || alert(m);
                        },
                        items: {
                            "view": {
                                name: "View",
                                icon: "fas fa-eye",
                                disabled: function(key, opt){
                                    if(selected == 1){
                                        return false
                                    }else{
                                        return true
                                    }
                                }
                                },
                            "download": {
                                name: "Download",
                                icon: "fas fa-download",
                                disabled: function(key, opt){
                                    if(selected == 1){
                                        return false
                                    }else{
                                        return true
                                    }
                                }
                                },
                            "sep1": "---------",
                            "copy": {name: "Copy", icon: "far fa-copy"},
                            "cut": {name: "Cut", icon: "fas fa-cut"},
                            "paste": {name: "Paste", icon: "fas fa-paste"},
                            "rename": {
                                name: "Rename",
                                icon: "fas fa-edit",
                                disabled: function(key, opt){
                                    if(selected == 1){
                                        return false
                                    }else{
                                        return true
                                    }
                                }
                                },
                            "zip": {name: "Zip", icon: "fas fa-file-archive"},
                            "sep2": "---------",
                            "delete": {name: "Delete", icon: "delete"},
                            "sep3": "---------",
                            "properties": {
                                name: "Properties",
                                icon: "far fa-list-alt",
                                disabled: function(key, opt){
                                    if(selected == 1){
                                        return false
                                    }else{
                                        return true
                                    }
                                }
                                },
                            }
                    }
                }
            });



        });

    </script>

    <script>


        $( document ).on("click", "#crop-image-file-manager", function() {

            $('#preview-img-file').css('display', 'none')
            $('.pre-crop-modal').attr('style', 'display: none !important');
            $('#crop-image-file-manager').css('display', 'none')
            $('#fm-additions-cropper').css('display', 'block')

            var image1 = $('#preview-img-file')
                var image = document.getElementById('img-cropper');


                cropper = new Cropper(image, {
                    preview: ".cropper-preview",
                    aspectRatio: Number($(image1).width()/$(image1).height(), 4),
                    viewMode: 0,
                    responsive: false,
                    dragMode: "move",
                    autoCropArea: 1,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    minContainerWidth: 750,
                    minContainerHeight: 300,
                    ready: function () {
                        //Should set crop box data first here
                        cropBoxData = {
                            width: 240,
                            height:  607,
                        },
                        canvasData = {
                            width: 750,
                            height: 300
                        }
                        cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                    },

                    crop(event) {
                        $('#dataX').val(Math.round(event.detail.x))
                        $('#dataY').val(Math.round(event.detail.y))
                        $('#dataHeight').val(Math.round(event.detail.height))
                        $('#dataWidth').val(Math.round(event.detail.width))
                        $('#dataRotate').val(Math.round(event.detail.rotate))
                        $('#dataScaleX').val(event.detail.scaleX)
                        $('#dataScaleY').val(event.detail.scaleY)

                    },

                    });
        })


        $( document ).on("click", "#back-btn-preview", function() {

            $('#preview-img-file').css('display', 'block')
            $('.pre-crop-modal').attr('style', 'display: block');
            $('#crop-image-file-manager').css('display', 'block')
            $('#fm-additions-cropper').css('display', 'none')
        })



        $( document ).on("click", ".fa-copy", function() {
            var copyText = $(this).parent().parent().find('.properties').text();

            document.execCommand("copy")

        })

        $( document ).on("click",".clear-files",function() {
            $('#clipboard-files').empty()
            files = []
        })

        $( document ).on("click","#zip-submit",function() {

            let name = $('#fm-zip-name').val()
            //alert(name)



            obj1 = {}
            arr = {}

            $.each(files, function(key, value){
                //console.log(value)
                obj1[key] = {}
                obj1[key] = value['path']
            })
            //console.log(obj1)


            arr = { files: obj1, directories: [] }


            data = { disk: 'uploads', name: name+'.zip', path: null, elements: arr}




            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: "/file-manager/zip",
                data: data,
                success: function(data) {
                    //console.log('data: '+data.new)
                    if(data){
                        $('#zipModal').modal('hide');

                        $.each($('.search-result'), function(key, value) {
                            //console.log($(value).find('td').data('name'))
                            old = data.old
                            var rest = old.substring(0, old.lastIndexOf("/") + 1);
                            var last = old.substring(old.lastIndexOf("/") + 1, old.length);

                            if($(value).find('td').data('name') == last){
                                let elem = $(value).find('td')[0]
                                //console.log(elem)

                                new_ = data.new
                                var rest = new_.substring(0, new_.lastIndexOf("/") + 1);
                                var last = new_.substring(new_.lastIndexOf("/") + 1, new_.length);

                                $(elem).data('name', last)
                                $(elem).data('path', data.new)
                                $(elem).text(last)
                            }
                        })
                    }
                }
            });
        })




        $( document ).on("click","#clipboard-toggle",function() {
            $('.fm-modal').hide();
            $('#clipboardModal').modal('toggle');

            let selected_elem = $('.table-info')
            //console.log(selected_elem)


            $.each(files, function(key, value) {
                //console.log()
                // let elem = $(value).find('td')[0]

                // name = $(elem).data('name')
                // path = $(elem).data('path')
                let row = `
                    <div  class="d-flex justify-content-between">
                        <div class="copy-file" data-id="${key}" data-path="${value['path']}" class="w-75 text-truncate"><span><i class="far fa-file"></i> ${value['name']} </span></div>
                        <div class="text-right">
                            <button type="button" title="Delete" class="close deleteFileFromClipboard"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                `

                $('#clipboard-files').append(row)
            })

            //$('#clipboard-files')

        })

        $( document ).on("change","#fm-input-rename",function() {
            $('#renameFile').removeAttr('disabled')
        })

        $( document ).on("change","#fm-zip-name",function() {
            $('#zip-submit').removeAttr('disabled')
        })




        $( document ).on("click", ".deleteFileFromClipboard", function() {
            let fileForDelete = $(this).parent().parent().find('.copy-file')
            let path = $(fileForDelete).data('path')

            $.each(files, function(key, value) {
                if(value.path == path){
                    files.splice(key, 1);
                    //console.log($(fileForDelete).parent().remove())

                }
            })
            $(this).parent().parent().remove()

            //console.log(index)
            // files.splice(id)
             //console.log(files)
        })


    $( document ).on("click","#renameFile",function() {
        let newName = $('#fm-input-rename').val()
        let oldName = $('#old-input-rename').val()
        let folder = $('#folder-ren').val()
        $('#renameFile').attr('disabled', true)


        newName = folder + newName
        oldName = folder + oldName

        data = {disk: 'uploads', newName: newName, oldName: oldName, type: 'file'}


        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: "/file-manager/rename",
            data: data,
            success: function(data) {
                //console.log('data: '+data.new)
                if(data){
                    $('#renameModal').modal('hide');

                    $.each($('.search-result'), function(key, value) {
                        //console.log($(value).find('td').data('name'))
                        old = data.old
                        var rest = old.substring(0, old.lastIndexOf("/") + 1);
                        var last = old.substring(old.lastIndexOf("/") + 1, old.length);

                        if($(value).find('td').data('name') == last){
                            let elem = $(value).find('td')[0]
                            //console.log(elem)

                            new_ = data.new
                            var rest = new_.substring(0, new_.lastIndexOf("/") + 1);
                            var last = new_.substring(new_.lastIndexOf("/") + 1, new_.length);

                            $(elem).data('name', last)
                            $(elem).data('path', data.new)
                            $(elem).text(last)
                        }
                    })
                }
            }
        });
    })

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
        search = `<div class="col-auto"><div role="group" class="btn-group">
                        <input id="inpuy-search" type="text" class="form-control form-control-alternative search_input" placeholder="Search">
                        <button class="btn btn-primary btn-sm" id="search_btn" type="button">Search</button>
                    </div></div>`
        $('#file_manager .fm-navbar .justify-content-between').append(search)


        var input = document.getElementById("inpuy-search");

        // Execute a function when the user releases a key on the keyboard
        input.addEventListener("keyup", function(event) {
            // Number 13 is the "Enter" key on the keyboard
            if (event.keyCode === 13) {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                document.getElementById("search_btn").click();
            }
            });
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
                        //console.log(value.path+'/'+value.name)
                        if(first_grid){
                            row = `<tr class="search-result"><td data-name="${value.name}" data-path='${value.path}/${value.name}' class="fm-content-item unselectable fm-download"><i class="far fa-file"></i> ${value.name} </td><td>${value.size}</td><td>${value.type}</td><td> ${value.modified} </td></tr>`
                            $('.fm-content-body tbody').append(row)
                        }else{
                            row =`<div title="fdf" class="fm-grid-item text-center unselectable active fm-download"><div class="fm-item-icon"><i class="fa-5x pb-2 far fa-folder"></i></div><div class="fm-item-info">${value.name}</div></div>`
                            $('.fm-content-body .d-flex.align-content-start.flex-wrap').appen(row)
                        }


                    })


                }
            });
    });

    $(document).on("click",".search-result", function (evt) {

        if (evt.ctrlKey){
            //console.log('asd')
            $(this).toggleClass("table-info");
        }else if(evt.which != 3){

            $('.table-info').removeClass('table-info')
            $(this).addClass('table-info')
        }

    });

    $(document).on("click","tr", function () {

        selected = $('.search-result.table-info').length

    });

    $(document).on("click",".close_modal", function () {

        $('.close').click()

    });

    $(document).mousedown(function(e){
        let elem = e.target

            if( e.button == 2 ) {
                //$("#test").removeClass("class-one").addClass("class-two");
                //$('.table-info').removeClass('table-info')

                if($(elem).parent().hasClass('search-result')){
                    $(elem).parent().addClass('table-info')
                }


                return false;
            }
            if(e.button == 0){
                if($(elem).parent().parent().hasClass('fm-tree-branch')){
                   $('.search-result').remove()
                }
            }
            return true;
        });





    </script>


@endpush

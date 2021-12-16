@extends("binshopsblog_admin::layouts.admin_layout")
@section("content")
<div class="card mb-0">
    <div class="card-header" style="border-bottom: 0px">
       <div class="row align-items-center">
          <div class="col-8">
             <a href="{{ route('binshopsblog.admin.index') }}" class="btn btn-sm btn-info"> <i class="fas fa-arrow-left"></i> {{ __('Back to Posts') }}</a>
            </div>
          <div class="col-4 text-right">
            <a href="{{ route('binshopsblog.admin.images.upload') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-circle"></i> {{ __('Upload Image') }}</a>
          </div>
       </div>
    </div>
    <div>
        <script>

            function show_uploaded_file_row(id, img) {

                // show the div, and populate it with the image.
                [].forEach.call(document.querySelectorAll('.' + id), function (el) {
                    el.style.display = 'block';
                });
                document.getElementById(id).innerHTML = "<a href='" + img + "'><img src='" + img + "' style='max-width:100%; height:auto;'></a>";


            }
        </script>
        @foreach($uploaded_photos as $uploadedPhoto)

            <div style='border-radius:15px; border:2px solid #efefef; background : #fefefe; margin: 15px;padding:15px'>
                        <h3>Image ID: {{$uploadedPhoto->id}}: {{$uploadedPhoto->image_title ?? "Untitled Photo"}}</h3>
                        <h4>
                            <small title='{{$uploadedPhoto->created_at}}'>
                                Uploaded {{$uploadedPhoto->created_at->diffForHumans()}}</small>
                        </h4>

                <div class='row'>
                    <div class='col-md-8'>
                        <div class='row' style=' margin: 10px; background: #eee; overflow:auto; padding:5px;'>

                            <?php
                            $smallest = null;
                            $smallest_size = -1;
                            foreach ($uploadedPhoto->uploaded_images as $file_key => $file) {
                            $id = "uploaded_" . ($uploadedPhoto->id) . "_" . $file_key;
                            ?>


                            <div class='col-md-12 '>
                                <h6 class='text-center mt-3'><strong>{{$file_key}}</strong> - {{$file['w']}}
                                    x {{$file['h']}}:</h6>
                                <p class='text-center'><a
                                            href='{{asset(     config("binshopsblog.blog_upload_dir") . "/". $file['filename'])}}'
                                            target='_blank'>[link]</a> / <span
                                            class='btn btn-sm btn-primary'
                                            style='cursor: zoom-in;'
                                            onclick='show_uploaded_file_row("{{$id}}","{{asset(     config("binshopsblog.blog_upload_dir") . "/". $file['filename'])}}")'>show</span>
                                </p>

                                <div id="{{$id}}"></div>
                            </div>
                            <div class='col-md-6 {{$id}}' style='display:none;'>
                                <div style=''>
                                    <small class='text-muted'>Image URL</small>
                                    <input type='text' readonly='readonly' class='form-control'
                                           value='{{asset(     config("binshopsblog.blog_upload_dir") . "/". $file['filename'])}}'>
                                </div>
                            </div>
                            <div class='col-md-6 {{$id}}' style='display:none;'>

                                <div style=''>
                                    <small class='text-muted'>img tag</small>
                                    <input type='text' readonly='readonly' class='form-control'
                                           value='{{"<img src='".asset(     config("binshopsblog.blog_upload_dir") . "/". $file['filename'])."' alt='" . e($uploadedPhoto->image_title) . "' >"}}'>
                                </div>
                            </div>

                            <?php

                            $area = $file['w'] * $file['h'];
                            if ($area < $smallest_size || $smallest_size < 0) {
                                $smallest = $file;
                                $smallest_size = $area;
                            }

                            }

                            ?>
                        </div>
                    </div>
                    <div class='col-md-4'>
                        @if($smallest)


                            <div style='text-align:center;'>
                                <a style='cursor: zoom-in;' href='{{asset(     config("binshopsblog.blog_upload_dir") . "/". $smallest['filename'])}}'
                                   target='_blank'>
                                    <img src='{{asset(     config("binshopsblog.blog_upload_dir") . "/". $smallest['filename'])}}'
                                         style='max-width:100%; height: auto;'>
                                </a>
                            </div>

                        @else

                            <div class='alert alert-danger'>No image found</div>
                        @endif


                    </div>
                </div>
            </div>

        @endforeach



        <div class='text-center'>
            {{$uploaded_photos->appends( [] )->links()}}
        </div>
    </div>
@endsection

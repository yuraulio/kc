<div class="row align-items-center med-versions">
   <div class="col-12">
      <h6 class="heading-small text-muted mb-4">{{ __('Image versions') }}</h6>
        <div class="row">
            <?php

            if($event['mediable_type'] == 'App\Model\Event' || $event['mediable_type'] == 'App\Model\Pages' || $event['mediable_type'] == 'App\Model\Logos' || $event['mediable_type'] == 'App\Model\Instructor' || $event['mediable_type'] == 'App\Model\Testimonial'){
                if($event['details'] != null){
                    $details = json_decode($event['details'], true);
                }else{
                    $details = null;
                }

                $versions = [];


                    foreach (get_image_versions() as $key => $version) {
                        foreach($versions1 as $version_from_include){
                            if($version_from_include == $version['version']){
                                $found = true;
                            }else{
                                $found = false;
                            }
                            if($found){
                                $versions[$key] = $version;
                            }

                        }

                    }
                    foreach($versions as $key1 => $value1){
                        $versions[$key1]['x'] = 0;
                        $versions[$key1]['y'] = 0;
                        if(!isset($details['img_align'])){
                            continue;
                        }
                        foreach( $details['img_align'] as $key => $value){
                            //dd($value);
                            if($key == $value1['version']){
                                $found1 = true;
                                //break;
                            }else{
                                $found1 = false;
                            }
                            if($found1){
                                //var_dump($value);
                                $versions[$key1]['x'] = $value['x'];
                                $versions[$key1]['y'] = $value['y'];

                                $versions[$key1]['w'] = $value['width'];
                                $versions[$key1]['h'] = $value['height'];
                                break;
                            }else{
                                $versions[$key1]['x'] = 0;
                                $versions[$key1]['y'] = 0;
                            }

                        }

                    }

            }else if($event['mediable_type'] == 'App\Model\User'){
                $versions = null;
                $details = json_decode($event['details'], true);
            }

            ?>

            @if($versions != null)
                @foreach($versions as $version)
                <div class="col-12">

                <div class="card" style="width: auto;">
                <div class="card-body">
                    {{--<h4 class="card-title">{{$version['version']}}</h4>--}}

                    <div class="card-version-image"><img id="{{$version['version']}}" class="img-fluid" data-version="{{$version['version']}}" src="<?php
                        if(isset($event)) {
                            echo url($event['path'].$event['original_name']);
                        }else{
                            echo '';
                        }
                    ?>" alt="Card image cap"></div>
                    <div class="card-version-info"><h3 class="card-text">{{$version['description']}}</h3></div>


                </div>
                </div>
                </div>



                @endforeach
                <div class="col crop-wrap-btn">
                <!-- <div class="status-crop d-none">Successfully cropped!!</div> -->
                <button class="btn btn-primary crop" data-version="{{$version['version']}}" type="button">Crop</button>
                    </div>
                @else
                <div class="col-12">


                    <div class="card" style="width: auto;">
                    <div class="card-body">
                        {{--<h4 class="card-title">@if($versions != null){{$version['version']}}@endif</h4>--}}
                        <div class="card-version-image"><img id="@if($versions != null){{$version['version']}}@else{{'profile_image'}}@endif" class="img-fluid" data-version="@if($versions != null){{$version['version']}}@endif" src="<?php
                            if(isset($event)) {
                                echo url($event['path'].$event['original_name']);
                            }else{
                                echo '';
                            }
                        ?>" alt="Card image cap"></div>
                        <div class="card-version-info"><h3 class="card-text">@if($versions != null){{$version['description']}}@endif</h3></div>


                    </div>
                    </div>
                    </div>
                    <div class="col crop-wrap-btn">
                        <button class="btn btn-primary crop_profile" type="button">Crop</button>
                    </div>

                @endif


        </div>
   </div>



</div>

@push('css')
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('js')
<script src="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>

<script>
    let ver = []
    let prof_image = false
    var versions = @json($versions);
    //console.log(versions)
    if("{{$event}}" && versions != null){
        image_details = @json($event);
        let myObj = {}
        let myArr = []


        $.each(versions, function(key, value){
            //console.log(document.getElementById(`${value.version}`))
            let name = value.version
            const cropper = new Cropper(document.getElementById(`${value.version}`), {
                aspectRatio: Number((value.w/value.h)).toFixed(4),
                viewMode: 1,
                dragMode: "crop",
                responsive: false,
                autoCropArea: 1,
                restore: false,
                movable: false,
                rotatable: false,
                scalable: false,
                zoomable: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                //minContainerWidth: 300,
                //minContainerHeight: 130,
                //minCanvasWidth: 300,
                //minCanvasHeight: 130,

                data:{
                    x:parseInt(value.x),
                    y:parseInt(value.y),
                    width: parseInt(value.w),
                    height: parseInt(value.h)
                }
            });

            // console.log(name)
            // console.log('w:',parseInt(value.w))
            // console.log('h:',parseInt(value.h))
            // console.log(Number((value.w/value.h).toFixed(4)))

            versions[key]['insta'] = cropper;

        })
    }else{
        prof_image = true
        image_details = null;
        $img = @json($event);


        if($img['name'] != '' && $img['details'] != null){
            image_details = JSON.parse($img['details'].split(','))
            width = image_details.width
            height = image_details.height
            x = image_details.x
            y = image_details.y


        }else{
            width = 800
            height = 800
            x = 0;
            y = 0;
        }





    }
    if(prof_image){
        const cropper = new Cropper(document.getElementById(`profile_image`), {
            aspectRatio: Number((width/height), 4),
            viewMode: 0,
            dragMode: "crop",
            responsive: true,
            autoCropArea: 0,
            restore: false,
            movable: false,
            rotatable: false,
            scalable: false,
            zoomable: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            minContainerWidth: 300,
            minContainerHeight: 300,
            // minCanvasWidth: 350,
            // minCanvasHeight: 350,

            data:{
                x:parseInt(x),
                y:parseInt(y),
                width: parseInt(width),
                height: parseInt(height)
            }
        });

        ver['0'] = cropper
    }


    $(".crop_profile").click(function(){
        cropper = ver[0]
        let media = @json($event);

        let path = $(this).parent().parent().find('img').attr('src')


        path = path.split('/')

        path = '/'+path[3]+'/' + path[4]+'/' + path[5]



        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/media/crop_profile_image',
            data: {'media_id': media.id,'path':path, 'x':cropper.getData({rounded: true}).x, 'y':cropper.getData({rounded: true}).y, 'width':cropper.getData({rounded: true}).width, 'height':cropper.getData({rounded: true}).height},
            success: function (data) {
                if(data){
                    $('#profile_image_msg').append(data.success)
                    $('#profile_image_msg').css('display', 'inline-block')
                }
            }
        });

    });


let status = false
    $(".crop").click(function(){
        let media = @json($event);
        let path = $(this).parent().parent().find('img').attr('src')


        $.each($('.card'), function(key, value) {
            img = $(value).find('img')[0]
            version = $(img).data('version')

            //console.log(path)

            $.each(versions, function(key,value){
                if(value.version == version){
                    cropper = value.insta

                    $.ajax({
                        async: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        url: '/admin/media/crop_image',
                        data: {'media_id': media.id,'version':version ,'path':path, 'x':cropper.getData({rounded: true}).x, 'y':cropper.getData({rounded: true}).y, 'width':cropper.getData({rounded: true}).width, 'height':cropper.getData({rounded: true}).height},
                        success: function (data) {
                            //console.log(data)
                            if(data){
                                status = true
                                // $('#msg_'+data.data.version).append(data.success)
                                // $('#msg_'+data.data.version).css('display', 'inline-block')
                            }
                        }
                    });
                }
            })
        })

        if(status){
            Swal.fire(
                'Good job!',
                'Successfully Cropped!',
                'success'
            )
        }


    });

</script>

@endpush

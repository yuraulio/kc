<div class="row align-items-center">
   <div class="col-12">
      <h6 class="heading-small text-muted mb-4">{{ __('Image versions') }}</h6>
        <div class="row">
            <?php
                //dd($event);

            if($event['details'] != null){
                $details = json_decode($event['details'], true);
            }else{
                $details = null;
            }

            $versions = [];

            //dd(get_image_versions());

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
                //dd($versions);
            ?>




            @foreach($versions as $version)

            <div class="col-12 img_version">
                <h3>{{$version['version']}}</h3>

                <img class="img-fluid" id="{{$version['version']}}" data-version="{{$version['version']}}" src="
                <?php
                    if(isset($event)) {
                        echo $event['path'].$event['original_name'];
                    }else{
                        echo '';
                    }
                ?>" alt="">

                <button class="btn btn-primary crop" data-version="{{$version['version']}}" type="button">Crop</button>
                <div class="crop-msg" id="msg_{{$version['version']}}"></div>
            </div>

            @endforeach

        </div>
   </div>

</div>

@push('js')

<script>
    if("{{$event}}"){
        image_details = @json($event)
    }else{
        image_details = null;
    }

    var versions = @json($versions);
    let myObj = {};
    let myArr = []

    $.each(versions, function(key, value){
        let name = value.version
        //console.log(value)
        const cropper = new Cropper(document.getElementById(`${value.version}`), {
            aspectRatio: Number((value.w/value.h), 4),
            viewMode: 0,
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
            minContainerWidth: 300,
            minContainerHeight: 300,
            // minCanvasWidth: 350,
            // minCanvasHeight: 350,

            data:{
                x:parseInt(value.x),
                y:parseInt(value.y),
                width: parseInt(value.w),
                height: parseInt(value.h)
            }
        });

        versions[key]['insta'] = cropper;

    })

    $(".crop").click(function(){
        let media = @json($event);
        let path = $(this).parent().find('img').attr('src')
        let version = $(this).data('version')

        console.log(path)

        $.each(versions, function(key,value){
            if(value.version == version){
                cropper = value.insta
            }
        })

        alert(cropper.getData({rounded: true}).height)


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/media/crop_image',
            data: {'media_id': media.id,'version':version ,'path':path, 'x':cropper.getData({rounded: true}).x, 'y':cropper.getData({rounded: true}).y, 'width':cropper.getData({rounded: true}).width, 'height':cropper.getData({rounded: true}).height},
            success: function (data) {
                //console.log(data)
                console.log(data.data.version)
                if(data){
                    $('#msg_'+data.data.version).append(data.success)
                    $('#msg_'+data.data.version).css('display', 'inline-block')
                }
            }
        });

    });

</script>

@endpush

<div class="row align-items-center">
   <div class="col-12">
      <h6 class="heading-small text-muted mb-4">{{ __('Image versions') }}</h6>
        <div class="row">
            <?php
            $versions = [];
                foreach (get_image_versions() as $key => $version) {
                    foreach($versions1 as $version_from_include){
                        if($version_from_include == $version['version']){
                            $versions[$key] = $version;
                        }
                    }

                }
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
                x:0,
                y:0,
                width: value.w,
                height: value.h
            }
        });

        versions[key]['insta'] = cropper;

    })

    $(".crop").click(function(){
        let path = $(this).parent().find('img').attr('src')
        let version = $(this).data('version')

        $.each(versions, function(key,value){
            if(value.version == version){
                cropper = value.insta
            }
        })

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/media/crop_image',
            data: {'version':version ,'path':path, 'x':cropper.getData({rounded: true}).x, 'y':cropper.getData({rounded: true}).y, 'width':cropper.getData({rounded: true}).width, 'height':cropper.getData({rounded: true}).height},
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

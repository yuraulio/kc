<div class="row align-items-center">
   <div class="col-12">
      <h3 class="mb-0">{{ __('Image versions') }}</h3>
        <div class="row">
            <?php
                //$parts = get_split_image_path($event->medias['original_name']);
            ?>
            <?php //dd(get_image_versions()); ?>
            @foreach(get_image_versions() as $version)
            <div class="col-12 img_version">
                <img class="img-fluid" id="{{$version['version']}}" data-version="{{$version['version']}}" src="<?php if(isset($event->medias)) {
                                                                echo $event->medias['path'].$event->medias['original_name'];
                                                            }else{
                                                                echo '';
                                                            }?>" alt="">
            </div>
            <button class="btn btn-primary crop" data-version="{{$version['version']}}" type="button">Crop</button>

            @endforeach
        </div>
   </div>

</div>

@push('js')

<script>

    var versions = @json(get_image_versions());
    console.log(versions)
    let myObj = {};
    let myArr = []

    $.each(versions, function(key, value){
        let name = value.version
        const cropper = new Cropper(document.getElementById(`${value.version}`), {
            responsive: true,
            movable: true,
            dragMode:"move",
            zoomable: true,

            minCropBoxWidth: value.w,

            minCropBoxHeight: value.h,

            minContainerWidth: 650,

            minContainerHeight: 400,
            cropBoxResizable: false,
            data:{
                    width: parseFloat(value.w),
                    height: parseFloat(value.h),
                },
            crop(event) {
                // console.log(event.detail.x);
                // console.log(event.detail.y);
                // console.log(event.detail.width);
                // console.log(event.detail.height);
            },
        });
        console.log('float'+parseFloat(value.w))
        versions[key]['insta'] = cropper;
    })

    $(".crop").click(function(){
        let path = $(this).parent().find('img').attr('src')
        //console.log('path is :'+path)
        let version = $(this).data('version')
        //console.log('version is :'+version)


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
            url: '/admin/events/crop_image',
            data: {'version':version ,'path':path, 'x':cropper.getData().x, 'y':cropper.getData().y, 'width':cropper.getData().width, 'height':cropper.getData().height},
            success: function (data) {
                //console.log(data)
            }
        });

    });

</script>

@endpush

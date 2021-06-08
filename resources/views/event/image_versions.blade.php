<div class="row align-items-center">
   <div class="col-12">
      <h3 class="mb-0">{{ __('Image versions') }}</h3>
        <div class="row">
            <?php
                //$parts = get_split_image_path($event->medias['original_name']);
            ?>
            <?php //dd(get_image_versions()); ?>
            @foreach(get_image_versions() as $version)
            <div style="text-align:center;" class="col-12 img_version">
                <img class="img-fluid" id="image1" src="{{$event->medias['path']}}{{$event->medias['original_name']}}" alt="">
            </div>

            <button class="btn btn-primary" id="crop" type="button">Crop</button>

            @endforeach
        </div>


                <!-- <img style="max-width:100%;" id="image" src="/argon/img/theme/angular.jpg"> -->




   </div>

</div>

@push('js')

<script>

    // As a Vanilla JS plugin
const cropper = new Cropper(document.getElementById('image1'), {
    responsive: true,
	movable: false,
    zoomable: false,
    minCanvasWidth: 700,

	minCanvasHeight: 500,

	minCropBoxWidth: 470,

	minCropBoxHeight: 470,

	minContainerWidth: 700,

	minContainerHeight: 500,
    crop(event) {
        console.log(event.detail.x);
        console.log(event.detail.y);
        console.log(event.detail.width);
        console.log(event.detail.height);
        console.log(event.detail.rotate);
        console.log(event.detail.scaleX);
        console.log(event.detail.scaleY);

        $("#crop").click(function(){
            canvas = cropper.getCroppedCanvas({
                width: event.detail.width,
                height: event.detail.height,
            });

            console.log(canvas)
        });
    },

});






</script>

@endpush

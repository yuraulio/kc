<div class="row align-items-center">
   <div class="col-12">
      <h3 class="mb-0">{{ __('Image versions') }}</h3>
        <div class="row">
            <?php
                $parts = get_split_image_path($event->medias['original_name']);
            ?>
            @foreach(get_image_versions() as $version)
            <div style="text-align:center;" class="col-12 img_version">
                <img class="img-fluid" src="{{$event->medias['original_name']}}" alt="">
            </div>

            @endforeach
        </div>


                <img style="max-width:100%;" id="image" src="/argon/img/theme/angular.jpg">




   </div>

</div>

@push('js')

<script>

    // As a Vanilla JS plugin
const cropper = new Cropper(document.getElementById('image'), {
    responsive: true,
	movable: false,
    minCanvasWidth: 200,

	minCanvasHeight: 200,

	minCropBoxWidth: 0,

	minCropBoxHeight: 0,

	minContainerWidth: 600,

	minContainerHeight: 400,
});



</script>

@endpush

<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Videos') }}</h3>
        <p class="text-sm mb-0">
            {{ __('This is an example of Video management.') }}
        </p>
    </div>
    <div class="col-4 text-right">
        <button data-toggle="modal" data-target="#videoModal" class="btn btn-sm btn-primary">{{ __('Assign videos') }}</button>
    </div>
</div>
<div class="table-responsive py-4">
    <table class="table align-items-center table-flush video-table" id="datatable-basic-video">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Url') }}</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <?php //dd($model->video); ?>
        <tbody class="video-body summaries-order">
        @if($model->video)
            @foreach ($model->video as $video)
                <tr>
                    <td id="title-{{$video->id}}" data-id="{{$video->id}}" class="video-list"><a class="edit-btn" href="#"> {{ $video->title }} </a></td>
                    <td id="description-{{$video->id}}" data-id="{{$video->id}}" class="video-list">{{ $video->description }}</td>
                    <td id="url-{{$video->id}}" data-id="{{$video->id}}" class="video-list">{{ $video->url }}</td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            {{--<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" data-toggle="modal" data-target="#editModalSummary" data-id="{{$summary->id}}" data-title="{{$summary->title}}" data-description="{{$summary->description}}" data-section="{{$summary->section}}" data-media="@isset($summary->medias){{ $summary->medias['path'] }}{{ $summary->medias['original_name'] }}@endisset" >{{ __('Edit') }}</a>
                            </div>--}}
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>


<!-- Modal -->

<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Assign Video</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- <h6 class="heading-small text-muted mb-4">{{ __('Benefit information') }}</h6> -->
                <div class="pl-lg-4">
                    <form>
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Select Video</label>
                            <select class="form-control" id="videoFormControlSelect">
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="button" data-event-id="{{$model->id}}" id="video_save_btn" class="btn btn-primary">Save changes</button>
            </div>

        </div>
    </div>
</div>

@push('js')
<script>




    $(document).on('click', '#video_save_btn',function(e) {
        alert('asd')

        let modelType = "{{addslashes ( get_class($model) )}}";
        let modelId = "{{ $model->id }}";

        var selected_option = $('#videoFormControlSelect option:selected');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("city.store")}}',
            data: {'city_id':$(selected_option).val(), 'model_type':modelType,'model_id':modelId},
            success: function (data) {
                let city = data.city

                $('.city-body tr').remove();


                let newCity =
                `<tr>` +
                `<td id="name-` + city['id'] +`">` + city['name'] + `</td>` +
                `

                </tr>`;

                $(".city-body").append(newCity);
                $(".close-modal").click();
                $("#success-message p").html(data.success);
                $("#success-message").show();


            }
        });

})
</script>
<script>
   $(document).on('click',"#edit-summary",function(){
   summaryId = $(this).attr('data-id')
   console.log(summaryId)
   $.ajax({
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
   	    type: 'post',
   	    url: '/admin/summary/update/' + summaryId,
            data: {'title':$('#edit-title').val(),'description':CKEDITOR.instances['edit-description2'].getData(), 'section': $('#editModalSummary #edit_section_sum').val(),'svg': $('#image_svg_upload-summary').val()},
   	    success: function (data) {

   	let summary = data.summary;


   	$("#title-"+summary['id']).html(`<a class="edit-btn" href="#">`+summary['title'])
   	$("#section_sum-"+summary['id']).html(summary['section'])
    $("#media_sum-"+summary['id']).html(summary.medias['path']+summary.medias['original_name'])
    $("#title-"+summary['id']).parent().find('.dropdown-item').attr('data-description', summary['description'])
    $("#title-"+summary['id']).parent().find('.dropdown-item').attr('data-media', summary.medias['path']+summary.medias['original_name'])
    $("#img-upload-summary").attr('src', summary.medias['path']+summary.medias['original_name'])
   	$(".close_modal").click();

   	$("#success-message p").html(data.success);
   	$("#success-message").show();

   	    },
   	    error: function() {
   	         //console.log(data);
   	    }
   	});



   })
</script>
<script>
   $(document).on('shown.bs.modal', '#editModalSummary',function(e) {
       alert('asd')
    $("#sum_create").trigger('reset')



   	var link  = e.relatedTarget,
        	modal    = $(this),
            id = e.relatedTarget.dataset.id

            // if open first modal with id pass data
            // if open second modal(file-manager) no pass data
            if(id != null){

            //title = e.relatedTarget.dataset.title,
            //description =e.relatedTarget.dataset.description;
            title = $("#title-"+id).text(),
            section = e.relatedTarget.dataset.section
            description = e.relatedTarget.dataset.description
            media = e.relatedTarget.dataset.media
            modal.find("#edit-title").val(title);
            CKEDITOR.instances['edit-description2'].setData(description)
                modal.find("#summary-id").val(id)
            //$("#summary-id").val('asd')
            $("#edit-summary").attr('data-id', id)
            $("#image_svg_upload-summary").val(media)
            modal.find("#edit-section_sum").val(section)

            base_url = window.location.protocol + "//" + window.location.host
            $("#img-upload-summary").attr('src', base_url+media)
            console.log(base_url+media)

            }




   });

</script>
<script src="{{ asset('js/sortable/Sortable.js') }}"></script>

<script>

   (function( $ ){

      var el = document.getElementsByClassName('summaries-order')[0];

      new Sortable(el, {
         group: "words",
         handle: ".my-handle",
         draggable: ".item",
         ghostClass: "sortable-ghost",

      });

      new Sortable(el, {

          // Element dragging ended
          onEnd: function ( /**Event*/ evt) {
            orderSummary()
          },
      });

   })( jQuery );

   function orderSummary(){
      let summaries={}

      $( ".summary-list" ).each(function( index ) {
            summaries[$(this).data('id')] = index
      });


      $.ajax({
         type: 'POST',
         headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         Accept: 'application/json',
         url: "{{ route ('sort-summaries', $event->id) }}",
         data:{'summaries':summaries},
         success: function(data) {


         }
      });
   }



</script>

@endpush



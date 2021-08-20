
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Summary') }}</h3>
        <p class="text-sm mb-0">
            {{ __('This is an example of Summary management.') }}
        </p>
    </div>
    <div class="col-4 text-right">
        <button data-toggle="modal" data-target="#summaryModal" class="btn btn-sm btn-primary">{{ __('Add summary') }}</button>
    </div>
</div>
<div class="table-responsive py-4">
    <table class="table align-items-center table-flush summary-table" id="datatable-basic-summary">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Icon') }}</th>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Section') }}</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <?php //dd($model); ?>
        <tbody class="summary-body summaries-order">
        @if($model->summary1)
            @foreach ($model->summary1 as $summary)
                <tr>
                    <td>
                        <img
                            id="sum_ben_icon-{{$summary->id}}"
                            class="sum_ben sum_ben_icon-{{$summary->id}}"
                            src="@isset($summary->medias)
                                    {{ asset('') }}{{$summary->medias['path']}}{{$summary->medias['original_name'] }}
                                @endisset"
                            onerror="this.src='https://via.placeholder.com/35'"
                        >
                    </td>
                    <td id="title-{{$summary->id}}" data-id="{{$summary->id}}" class="summary-list"><a class="edit-btn" href="#"> {{ $summary->title }} </a></td>
                    <td id="section_sum-{{$summary->id}}" data-id="{{$summary->id}}" class="summary-list">{{ $summary->section }}</td>
                    <td hidden id="media_sum-{{$summary->id}}" data-id="{{$summary->id}}" class="summary-list">
                    @isset($summary->medias)
                    {{ $summary->medias['path'] }}{{ $summary->medias['original_name'] }}
                    @endisset
                    </td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" data-toggle="modal" data-target="#editModalSummary" data-id="{{$summary->id}}" data-title="{{$summary->title}}" data-description="{{$summary->description}}" data-section="{{$summary->section}}" data-media="@isset($summary->medias){{ $summary->medias['path'] }}{{ $summary->medias['original_name'] }}@endisset" >{{ __('Edit') }}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>


<!-- Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('Create summary') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Summary information') }}</h6>
            <div class="pl-lg-4">
                <form id="sum_create">
                    <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-title-summary">{{ __('Title') }}</label>
                        <input type="text" name="title" id="input-title-summary" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>
                        @include('alerts.feedback', ['field' => 'title'])
                    </div>
                    <div class="form-group{{ $errors->has('description2') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-description2">{{ __('Description') }}</label>
                        <textarea name="description4" id="input-description4" class="ckeditor form-control{{ $errors->has('description2') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}"></textarea>
                        @include('alerts.feedback', ['field' => 'description4'])
                    </div>
                    <div class="form-group{{ $errors->has('section_sum') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-section_sum">{{ __('Section') }}</label>

                        <select name="section_sum" id="input-section_sum" class="form-control" placeholder="{{ __('Section') }}">
                            <option value="date">Date</option>
                            <option value="language">Language</option>
                            <option value="duration">Duration</option>
                            <option value="students"> Students</option>
                            <option value="access"> Access </option>
                            <option value="diploma"> Diploma </option>
                            <option value="exams"> Exams</option>
                        </select>
                        @include('alerts.feedback', ['field' => 'section_sum'])
                    </div>

                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
            <button type="button" id="save_summary" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModalSummary" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit summary</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Summary information') }}</h6>
            <div class="pl-lg-4">
               <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-title">{{ __('Title') }}</label>
                  <input type="text" name="title" id="edit-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>
                  @include('alerts.feedback', ['field' => 'title'])
               </div>
               <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-description">{{ __('Description') }}</label>
                  <textarea name="description" id="edit-description2" class="ckeditor form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}">{{ old('description') }}</textarea>
                  @include('alerts.feedback', ['field' => 'description'])
               </div>
               <div class="form-group{{ $errors->has('section_sum') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-section_sum">{{ __('Section') }}</label>
                    <select name="section_sum" id="edit_section_sum" class="form-control" placeholder="{{ __('Section') }}">
                        <option value="date">Date</option>
                        <option value="language">Language</option>
                        <option value="duration">Duration</option>
                        <option value="students"> Students</option>
                        <option value="access"> Access </option>
                        <option value="diploma"> Diploma </option>
                        <option value="exams"> Exams</option>
                    </select>
                  @include('alerts.feedback', ['field' => 'section_sum'])
               </div>
               <?php //dd($model->summary1); ?>

               @if(isset($model->summary1))
               <?php //$media = $model->summary->medias;
               $media = null; ?>
               @else
               <?php $media = null; ?>
               @endif

               @include('admin.summary.upload_svg', ['data' => $media, 'template1' => 'summary'])
               <input type="text" id="summary-id"  value="" hidden>
               <input type="hidden" value="" name="image_svg_upload" id="image_svg_upload-summary">
            </div>
            <div class="form-group" style="text-align:center;">
                <img style="margin-top:10px;" id="img-upload-summary" onerror="this.src='https://via.placeholder.com/35'" src="">
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
            <button type="button" id="edit-summary" data-id="" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.css">
@endpush
@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script>

    $('#datatable-basic-summary').DataTable({
        language: {
            paginate: {
            next: '&#187;', // or '→'
            previous: '&#171;' // or '←'
            }
        }
    });




   $(document).on('click',"#save_summary",function(){
   let modelType = "{{addslashes ( get_class($model) )}}";
   let modelId = "{{ $model->id }}";

        $.ajax({
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
   	    type: 'post',
   	    url: '{{route("summary.store")}}',
            data: {'title':$('#input-title-summary').val(),'section':$('#input-section_sum').val(),'description':CKEDITOR.instances['input-description4'].getData(),'model_type':modelType,'model_id':modelId},
   	    success: function (data) {
   	console.log(data);
   	let summary = data.summary;
   	let newSummary =
   	`<tr>` +
    `<td>
        <img
            id="sum_ben_icon-${summary['id']}"
            class="sum_ben sum_ben_icon-${summary['id']}"
            src=""
            onerror="this.src='https://via.placeholder.com/35'"
        >
    </td>`+
   	`<td id="title-` + summary['id'] +`"><a class="edit-btn" href="#">` + summary['title'] + `</a></td>` +
    `<td id="section_sum-` + summary['id'] +`">` + summary['section'] + `</td>` +
    `<td hidden id="media_sum-` + summary['id'] +`" data-id="` + summary['id'] +`" class="summary-list"></td>`+

      `<td class="text-right">
               <div class="dropdown">
                  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                     <a class="dropdown-item" data-toggle="modal" data-target="#editModalSummary" data-id="` + summary['id'] + `" data-title="`+summary['title'] +`" data-description="`+ summary['description']+`">{{ __('Edit') }}</a>

                  </div>
               </div>
            </td>

   	</tr>`;



    if($('.summary-body').find('.dataTables_empty').length == 1){
        let elem = $('.summary-body').find('.dataTables_empty')[0]
        $(elem).parent().remove()
    }

   	$(".summary-body").append(newSummary);
   	$(".close_modal").click();
   	$("#success-message p").html(data.success);
   	$("#success-message").show();
    $("#sum_create").trigger('reset')
   	    },
   	    error: function() {
   	         //console.log(data);
   	    }
   	});



   })
</script>
<script>
   $(document).on('click',"#edit-summary",function(){
   summaryId = $(this).attr('data-id')
   $.ajax({
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
   	    type: 'post',
   	    url: '/admin/summary/update/' + summaryId,
            data: {'title':$('#edit-title').val(),'description':CKEDITOR.instances['edit-description2'].getData(), 'section': $('#editModalSummary #edit_section_sum').val(),'svg': $('#image_svg_upload-summary').val()},
   	    success: function (data) {
               console.log('/////////')
               console.log(data)

   	let summary = data.summary;


       if(data.summary.medias !== undefined){
           let media = summary.medias
       }





   	$("#title-"+summary['id']).html(`<a class="edit-btn" href="#">`+summary['title'])
   	$("#section_sum-"+summary['id']).html(summary['section'])

    if(media !== undefined){
        console.log('-----')
        console.log(media)
        $("#media_sum-"+summary['id']).text(media)
        $("#sum_ben_icon-"+summary['id']).attr('src',media)[0]
        $("#title-"+summary['id']).parent().find('.dropdown-item').attr('data-media', media)
        $("#img-upload-summary").attr('src', media)
    }

    $("#title-"+summary['id']).parent().find('.dropdown-item').attr('data-description', summary['description'])

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




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
    <table class="table align-items-center table-flush"  id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Description') }}</th>
                <th scope="col">{{ __('Icon') }}</th>
                <th scope="col">{{ __('Created at') }}</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php //dd($model->summary()->get()); ?>
        @if($model->summary()->get())
            @foreach ($model->summary()->get() as $summary)
                <tr>
                    <td id="title-{{$summary->id}}">{{ $summary->title }}</td>
                    <td id="desc-{{$summary->id}}">{{ $summary->description }}</td>
                    <td id="icon-{{$summary->id}}">{{ $summary->icon }}</td>
                    <td>{{ date_format($summary->created_at, 'Y-m-d' ) }}</td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" data-toggle="modal" data-target="#editModalSummary" data-id="{{$summary->id}}" data-title="{{$summary->title}}" data-description="{{$summary->description}}" data-icon="{{$summary->icon}}">{{ __('Edit') }}</a>
                                {{--@can('update', $user)
                                    <a class="dropdown-item" href="{{ route('summary.edit', $summary) }}">{{ __('Edit') }}</a>
                                @endcan--}}
                                {{--@can('delete', $user)
                                    <form action="{{ route('summary.destroy', $summary) }}" method="post">
                                        @csrf
                                        @method('delete')

                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this summary?") }}') ? this.parentElement.submit() : ''">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                @endcan--}}

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
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Summary information') }}</h6>
            <div class="pl-lg-4">
               <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                  <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>
                  @include('alerts.feedback', ['field' => 'title'])
               </div>
               <div class="form-group{{ $errors->has('description2') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-description2">{{ __('Description') }}</label>
                  <input type="text" name="description2" id="input-description2" class="form-control{{ $errors->has('description2') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description2') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'description2'])
               </div>
               <div class="form-group{{ $errors->has('icon') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-icon">{{ __('Icon') }}</label>
                  <input type="text" name="icon" id="input-icon" class="form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}" placeholder="{{ __('Icon') }}" value="{{ old('icon') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'icon'])
               </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="save_summary" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModalSummary" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
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
                  <input type="text" name="description" id="edit-description2" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'description'])
               </div>
               <div class="form-group{{ $errors->has('icon') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-icon">{{ __('Icon') }}</label>
                  <input type="text" name="icon" id="edit-icon" class="form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}" placeholder="{{ __('Icon') }}" value="{{ old('icon') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'icon'])
               </div>
               <input type="text" id="summary-id"  value="" hidden>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="edit-summary" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>

@push('js')
<script>
   $(document).on('click',"#save_summary",function(){
       alert($('#input-description2').val())
   let modelType = "{{addslashes ( get_class($model) )}}";
   let modelId = "{{ $model->id }}";

        $.ajax({
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
   	    type: 'post',
   	    url: '{{route("summary.store")}}',
            data: {'title':$('#input-title').val(),'description':$('#input-description2').val(),'icon':$('#input-icon').val(),'model_type':modelType,'model_id':modelId},
   	    success: function (data) {
   	//console.log(data);
   	let summary = data.summary;
   	let newSummary =
   	`<tr>` +
   	`<td id="title-` + summary['id'] +`">` + summary['title'] + `</td>` +
   	`<td id="desc-` + summary['id'] +`">` + summary['description'] + `</td>` +
    `<td id="icon-` + summary['id'] +`">` + summary['icon'] + `</td>` +

   	`<td>` + summary['created_at'] + `</td>` +

      `<td class="text-right">
               <div class="dropdown">
                  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                     <a class="dropdown-item" data-toggle="modal" data-target="#editModalSummary" data-id="` + summary['id'] + `" data-title="`+summary['title'] +`" data-description="`+ summary['description'] + `" data-icon="`+summary['icon'] +`">{{ __('Edit') }}</a>

                  </div>
               </div>
            </td>

   	</tr>`;


   	$(".summary-body").append(newBenefit);
   	$(".close-modal").click();
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
   $(document).on('click',"#edit-summary",function(){
    alert($('#edit-icon').val())
   $summaryId = $("#summary-id").val()
   $.ajax({
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
   	    type: 'put',
   	    url: '/admin/summary/' + $summaryId,
            data: {'title':$('#edit-title').val(),'description':$('#edit-description2').val(),'icon':$('#edit-icon').val()},
   	    success: function (data) {

   	let summary = data.summary;

   	$("#title-"+summary['id']).html(summary['title'])
   	$("#desc-"+summary['id']).html(summary['description'])
    $("#icon-"+summary['id']).html(summary['icon'])
   	$(".close-modal").click();

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

   	var link  = e.relatedTarget,
        	modal    = $(this),
         id = e.relatedTarget.dataset.id
         //title = e.relatedTarget.dataset.title,
         //description =e.relatedTarget.dataset.description;
         title = $("#title-"+id).text(),
         description = $("#desc-"+id).text();
         icon = $("#icon-"+id).text();
         alert(title)

      modal.find("#edit-title").val(title);
      modal.find("#edit-description2").val(description);
      modal.find("#edit-icon").val(icon);
   	modal.find("#summary-id").val(id)

   });

</script>
@endpush



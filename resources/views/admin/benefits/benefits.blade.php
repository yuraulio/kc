<div class="row align-items-center">
   <div class="col-8">
      <h3 class="mb-0">{{ __('Benefits') }}</h3>
      <p class="text-sm mb-0">
         {{ __('This is an example of Benefits management.') }}
      </p>
   </div>
   <div class="col-4 text-right">
      <button data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-primary">{{ __('Add benefit') }}</button>
   </div>
</div>
<div class="table-responsive py-4">
   <table class="table align-items-center table-flush"  id="datatable-basic">
      <thead class="thead-light">
         <tr>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('Created at') }}</th>
            <th scope="col"></th>
         </tr>
      </thead>
      <tbody class="benefit-body">
         <?php //dd($model->benefits); ?>
         @if($model->benefits)
         @foreach ($model->benefits as $benefit)
         <tr>
            <td id="name-{{$benefit->id}}">{{ $benefit->name }}</td>
            <td>{{ date_format($benefit->created_at, 'd-m-Y' ) }}</td>
            <td class="text-right">
               <div class="dropdown">
                  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                     <a class="dropdown-item" data-toggle="modal" data-target="#editModal" data-id="{{$benefit->id}}" data-name="{{$benefit->name}}" data-description="{{$benefit->description}}">{{ __('Edit') }}</a>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="benefitModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="benefitModalLabel">Create benefit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Benefit information') }}</h6>
            <div class="pl-lg-4">
            <form id="benefit-form" >
               <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                  <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>
                  @include('alerts.feedback', ['field' => 'name'])
               </div>
               <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                  <textarea name="description" id="input-description" class="ckeditor form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}"></textarea>
                  @include('alerts.feedback', ['field' => 'description'])
               </div>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="save-benefit" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="benefitModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="benefitModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Benefit information') }}</h6>
            <div class="pl-lg-4">
            <form id="benefit-form-edit">
               <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-name">{{ __('Name') }}</label>
                  <input type="text" name="name" id="edit-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>
                  @include('alerts.feedback', ['field' => 'name'])
               </div>
               <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-description">{{ __('Description') }}</label>
                  <textarea name="description" id="edit-description1" class="ckeditor form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}">{{ old('description') }}</textarea>
                  @include('alerts.feedback', ['field' => 'description'])
               </div>
               <input type="text" id="benefit-id"  value="" hidden>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="edit-benefit" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>
@push('js')
<script>
   $(document).on('click',"#save-benefit",function(){

   let modelType = "{{addslashes ( get_class($model) )}}";
   let modelId = "{{ $model->id }}";

        $.ajax({
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
   	    type: 'post',
   	    url: '{{route("benefit.store")}}',
            data: {'name':$('#input-name').val(),'description':CKEDITOR.instances['input-description'].getData(),'model_type':modelType,'model_id':modelId},
   	    success: function (data) {
   	//console.log(data);
   	let benefit = data.benefit;
   	let newBenefit =
   	`<tr>` +
   	`<td id="name-` + benefit['id'] +`">` + benefit['name'] + `</td>` +
   	`<td>` + benefit['created_at'] + `</td>` +

      `<td class="text-right">
               <div class="dropdown">
                  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                     <a class="dropdown-item" data-toggle="modal" data-target="#editModal" data-id="` + benefit['id'] + `" data-name="`+benefit['name'] +`" data-description="`+ benefit['description'] + `">{{ __('Edit') }}</a>

                  </div>
               </div>
            </td>

   	</tr>`;


   	$(".benefit-body").append(newBenefit);
   	$(".close-modal").click();
   	$("#success-message p").html(data.success);
   	$("#success-message").show();
       $('#benefit-form').trigger('reset');
   	    },
   	    error: function() {
   	         //console.log(data);
   	    }
   	});



   })
</script>
<script>
   $(document).on('click',"#edit-benefit",function(){

   $benefitId = $("#benefit-id").val()
   $.ajax({
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
   	    type: 'put',
   	    url: '/admin/benefit/' + $benefitId,
            data: {'name':$('#edit-name').val(),'description':CKEDITOR.instances['edit-description1'].getData()},
   	    success: function (data) {

   	let benefit = data.benefit;

   	$("#name-"+benefit['id']).html(benefit['name'])
       $("#name-"+benefit['id']).parent().find('.dropdown-item').attr('data-description', benefit['description'])
       $('#benefit-form-edit').trigger('reset');
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
   $(document).on('shown.bs.modal', '#editModal',function(e) {
       //e.preventDefault()


   	var link  = e.relatedTarget,
        	modal    = $(this),
         id = e.relatedTarget.dataset.id
         //name = e.relatedTarget.dataset.name,
         //description =e.relatedTarget.dataset.description;
         name = $("#name-"+id).text(),

         modal.find("#benefitModalLabel").val(name)
         description = e.relatedTarget.dataset.description

      modal.find("#edit-name").val(name);
      CKEDITOR.instances['edit-description1'].setData(description)
   	    modal.find("#benefit-id").val(id)

   });


</script>
@endpush

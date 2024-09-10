<div class="row align-items-center">
   <div class="col-8">
      <h3 class="mb-0">{{ __('Benefits') }}</h3>
   </div>
   <div class="col-4 text-right">
      <button data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-primary">{{ __('Add benefit') }}</button>
   </div>
</div>

<?php

   $id = isset($sections['benefits'][0]) ? $sections['benefits'][0]['id'] : '';
   $tab_title = isset($sections['benefits'][0]) ? $sections['benefits'][0]['tab_title'] : '' ;
   $title = isset($sections['benefits'][0]) ? $sections['benefits'][0]['title'] : '' ;
   $visible = isset($sections['benefits'][0]) ? $sections['benefits'][0]['visible'] : false ;

?>


<div class="form-group">

   <input hidden name="sections[benefits][id]" value="{{$id}}">

   <label class="form-control-label" for="input-title">{{ __('Tab Title') }}</label>
   <input type="text" name="sections[benefits][tab_title]" class="form-control" placeholder="{{ __('Tab Title') }}" value="{{ old("sections[benefits][tab_title]", $tab_title) }}" autofocus>
   <label class="form-control-label" for="input-title">{{ __('H2 Title') }}</label>
   <input type="text" name="sections[benefits][title]" class="form-control" placeholder="{{ __('H2 Title') }}" value="{{ old("sections[benefits][title]", $title) }}" autofocus>


   <label class="form-control-label" for="input-method">{{ __('Visible') }}</label>
   <div style="margin: auto;" class="form-group">

       <label class="custom-toggle enroll-toggle visible">
           <input type="checkbox"  name="sections[benefits][visible]" @if($visible) checked @endif>
           <span class="custom-toggle-slider rounded-circle" data-label-off="no visible" data-label-on="visible"></span>
       </label>

   </div>


</div>


<div class="table-responsive py-4">
   <table class="table align-items-center table-flush benefits-table"  id="datatable-basic-benefits">
      <thead class="thead-light">
         <tr>
            <th scope="col">{{ __('Icon') }}</th>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('Created at') }}</th>
            <th scope="col"></th>
         </tr>
      </thead>
      <tbody class="benefit-body benefits-order">
         @if($model->benefits)
         @foreach ($model->benefits as $benefit)
         <tr>
            <td>
                <img
                    class="sum_ben_icon sum_ben_icon-{{$benefit->id}}"
                    src="@isset($benefit->medias)
                            {{ asset('') }}{{$benefit->medias['path']}}{{$benefit->medias['original_name'] }}
                        @endisset"
                    alt=""
                    onerror="this.src='https://via.placeholder.com/60?text=PHOTO'"
                >
            </td>
            <td id="name-{{$benefit->id}}" class="benefit-list" data-id ="{{$benefit->id}}"><a class="edit-btn" href="#">{{ $benefit->name }}</td>
            <td>{{ date_format($benefit->created_at, 'd-m-Y' ) }}</td>
            <td hidden id="media_ben-{{$benefit->id}}" data-id="{{$benefit->id}}" class="benefit-list">
                    @isset($benefit->medias)
                    {{ $benefit->medias['path'] }}
                    @endisset
                    </td>
            <td class="text-right">
               <div class="dropdown">
                  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                     <a class="dropdown-item" data-toggle="modal" data-target="#editModal" data-id="{{$benefit->id}}" data-name="{{$benefit->name}}" data-description="{{$benefit->description}}" data-media="@isset($benefit->medias){{$benefit->medias['path']}}{{$benefit->medias['original_name']}}@endisset">{{ __('Edit') }}</a>
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

               @if(isset($model->summary1))
               <?php $media = null;//$model->summary1->medias; ?>
               @else
               <?php $media = null; ?>
               @endif

               @include('admin.summary.upload_svg', ['data' => $media, 'template1' => 'benefit'])

               <input type="text" id="benefit-id"  value="" hidden>
               <input type="hidden" value="" name="image_svg_upload" id="image_svg_upload-benefit" >
            </form>

            <div class="form-group" style="text-align:center;">
                <img style="margin-top:10px;" id="img-upload-benefit" onerror="this.src='https://via.placeholder.com/60?text=PHOTO'" src="">
            </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="edit-benefit" data-id="" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>
@push('js')
<script>
    // $(document).on('click',".edit-btn",function(){
    //     $(this).parent().parent().find('.dropdown-item').click()
    // })

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
   	let benefit = data.benefit;
   	let newBenefit =
   	`<tr>` +
       `<td>
       <img
            class="sum_ben_icon sum_ben_icon-${benefit.id}"
            src="@isset($benefit->medias)
                    {{ asset('') }}{{$benefit->medias['path']}}{{$benefit->medias['original_name'] }}
                @endisset"
            alt=""
            onerror="this.src='https://via.placeholder.com/60?text=PHOTO'"
                >
       </td>`+
   	`<td id="name-` + benefit['id'] +`"><a class="edit-btn" href="#">` + benefit['name'] + `</td>` +
   	`<td>` + benefit['created_at'] + `</td>` +
       `<td hidden id="media_ben-` + benefit['id'] +`" data-id="` + benefit['id'] +`" class="benefit-list"></td>`+

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



    $(document).on('click',".close_modal",function(){
        $('.modal-backdrop').remove();
    })

    $(document).on('click',".close_svg_modal",function(){

        if($('.select_Svg_Modal-summary').hasClass('show')){
            $('.select_Svg_Modal-summary').modal('toggle');
            $('.select_Svg_Modal-benefit').modal('toggle');
        }
    })


    $(document).on('click',".close-modal",function(){
        $('#editModal').removeClass('show')
        $('.modal-backdrop').remove()
    })



    $(document).on('click',"#edit-benefit",function(){

        //$benefitId = $("#benefit-id").val()
        benefitId = $(this).attr('data-id')
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'put',
            url: '/admin/benefit/' + benefitId,
            data: {'name':$('#edit-name').val(),'description':CKEDITOR.instances['edit-description1'].getData(),'svg': $('#image_svg_upload-benefit').val()},
            success: function (data) {
                let benefit = data.benefit;

                if(data.benefit.medias !== undefined){
                    let media = data.benefit.medias
                    media = media.path+media.original_name

                    $(".sum_ben_icon-"+benefit['id']).attr('src',media)[0]

                    $("#name-"+benefit['id']).parent().find('.dropdown-item').attr('data-media', media)
                    $("#media_ben-"+benefit['id']).text(media)
                    $("#img-upload-benefit").attr('src', media)
                }

                $("#name-"+benefit['id']).html(`<a class="edit-btn" href="#">`+benefit['name'])
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

         if(id != null){
            //name = e.relatedTarget.dataset.name,
            //description =e.relatedTarget.dataset.description;
            name = $("#name-"+id).text(),

            modal.find("#benefitModalLabel").val(name)
            description = e.relatedTarget.dataset.description
            media = e.relatedTarget.dataset.media

            modal.find("#edit-name").val(name);
            CKEDITOR.instances['edit-description1'].setData(description)
            //modal.find("#benefit-id").val(id)
            $("#edit-benefit").attr('data-id', id)
            $("#image_svg_upload-benefit").val(media)
            $("#img-upload").attr('src', media)
            base_url = window.location.protocol + "//" + window.location.host
            $("#img-upload-benefit").attr('src', base_url+media)
         }

   });


</script>


<script src="{{ asset('js/sortable/Sortable.js') }}"></script>

<script>

   (function( $ ){



      var el = document.getElementsByClassName('benefits-order')[0];

      new Sortable(el, {
         group: "words",
         handle: ".my-handle",
         draggable: ".item",
         ghostClass: "sortable-ghost",

      });

      new Sortable(el, {

          // Element dragging ended
          onEnd: function ( /**Event*/ evt) {
            orderBenefits()
          },
      });

   })( jQuery );

   function orderBenefits(){
      let benefits={}

      $( ".benefit-list" ).each(function( index ) {
          benefits[$(this).data('id')] = index
      });


      $.ajax({
         type: 'POST',
         headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         Accept: 'application/json',
         url: "{{ route ('sort-benefits') }}",
         data:{'benefits':benefits,'id':"{{$model->id}}",'modelType':"{{addslashes ( get_class($model) )}}"},
         success: function(data) {


         }
      });
   }

   $(document).ready( function () {
      $('.benefits-table').dataTable( {
          "ordering": false
      });
   });

</script>

@endpush

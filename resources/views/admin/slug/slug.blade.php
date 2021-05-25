
@if(!$slug)
<div class="form-group{{ $errors->has('slug') ? ' has-danger' : '' }}">
   <label class="form-control-label" for="input-title">{{ __('Slug') }}</label>
   <input type="text" name="slug" id="input-slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" placeholder="{{ __('Slug') }}" value="{{ old('slug') }}"  required autofocus readonly>
   @include('alerts.feedback', ['field' => 'slug'])
</div>
@else
<div class="form-group{{ $errors->has('slug') ? ' has-danger' : '' }}">
   <label class="form-control-label" for="input-title">{{ __('Slug') }}</label>
   <input type="text" id="input-old-slug" value="{{ $slug->slug }}" hidden>
   
   <div class="d-flex">
      <input type="text" name="slug" id="input-slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }} col-9" placeholder="{{ __('Slug') }}" value="{{ old('slug',$slug->slug) }}"  required autofocus readonly> 
      <button class="btn btn-primary" id="edit-slug" type="button"> Edit </edit>
      <button class="btn btn-success" style="display:none" id="update-slug" type="button" > Update </edit>
      <button class="btn btn-danger" style="display:none" id="cancel-slug" type="button"> Cancel </edit>
   </div>
   
   @include('alerts.feedback', ['field' => 'slug'])
</div>
@endif

@push('js')
@if(!$slug)
   
   <script>
   	$(document).on('click',function(){

   		if($("#input-title").val() && !$("#input-slug").val()){

   			$.ajax({
   			    type: 'GET', 
   			    url: '/slug/create/' + $('#input-title').val(),
   			    success: function (data) {
   					$("#input-slug").val(data.slug)
   			    },
   			    error: function() { 
   			         //console.log(data);
   			    }
   			});

   		}

   	})
   </script>

@else

   <script>
   	$(document).on('click',"#edit-slug",function(){
         $("#edit-slug").hide();
         $("#update-slug").show();
         $("#cancel-slug").show();

         $('#input-slug').prop('readonly', false);

   	})
   </script>

   <script>
   	$(document).on('click',"#update-slug",function(){
         
   		if($('#input-slug').val() != $('#input-old-slug').val()){
            
            $.ajax({
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
   			    type: 'post', 
   			    url: '/slug/update/' + "{{$slug->id}}",
                data: {'slug':$('#input-slug').val()},
   			    success: function (data) {
   					$("#input-slug").val(data.slug);
                  $("#input-old-slug").val(data.slug);
                  $("#edit-slug").show();
                  $("#update-slug").hide();
                  $("#cancel-slug").hide();
                  $('#input-slug').prop('readonly', true);
   			    },
   			    error: function() { 
   			         //console.log(data);
   			    }
   			});

         }

   	})
   </script>

   <script>
   	$(document).on('click',"#cancel-slug",function(){

   		$("#edit-slug").show();
         $("#update-slug").hide();
         $("#cancel-slug").hide();
         $('#input-slug').prop('readonly', true);

   	})
   </script>

@endif

@endpush
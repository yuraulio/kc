
<?php if(!$slug): ?>

<div class="form-group<?php echo e($errors->has('slug') ? ' has-danger' : ''); ?>">
   <label class="form-control-label" for="input-title"><?php echo e(__('Slug')); ?></label>
   <input type="text" name="slug" id="input-slug" class="form-control<?php echo e($errors->has('slug') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Slug')); ?>" value="<?php echo e(old('slug')); ?>"  required autofocus readonly>
   <?php echo $__env->make('alerts.feedback', ['field' => 'slug'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php else: ?>
<div class="form-group<?php echo e($errors->has('slug') ? ' has-danger' : ''); ?>">
   <label class="form-control-label" for="input-title"><?php echo e(__('Slug')); ?></label>
   <input type="text" id="input-old-slug" value="<?php echo e($slug->slug); ?>" hidden>

   <div class="d-flex">
      <input type="text" name="slug" id="input-slug" class="form-control<?php echo e($errors->has('slug') ? ' is-invalid' : ''); ?> col-9" placeholder="<?php echo e(__('Slug')); ?>" value="<?php echo e(old('slug',$slug->slug)); ?>"  required autofocus readonly>
      <button class="btn btn-primary" id="edit-slug" type="button"> Edit </edit>
      <button class="btn btn-success" style="display:none" id="update-slug" type="button" > Update </edit>
      <button class="btn btn-danger" style="display:none" id="cancel-slug" type="button"> Cancel </edit>
   </div>

   <?php echo $__env->make('alerts.feedback', ['field' => 'slug'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php endif; ?>

<?php $__env->startPush('js'); ?>
<?php if(!$slug): ?>

   <script>
   	$(document).on('keyup',function(){

   		if($("#input-title").val()){

   			$.ajax({
   			    type: 'GET',
   			    url: '/admin/slug/create/' + $('#input-title').val(),
   			    success: function (data) {
   					$("#input-slug").val(data.slug)
   			    },
   			    error: function() {
   			         //console.log(data);
   			    }
   			});

   		}else if($("#input-name").val()){
            $.ajax({
   			    type: 'GET',
   			    url: '/admin/slug/create/' + $('#input-name').val(),
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

<?php else: ?>

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
   			    url: '/admin/slug/update/' + "<?php echo e($slug->id); ?>",
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

<?php endif; ?>

<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/slug/slug.blade.php ENDPATH**/ ?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary upload-img" data-toggle="modal" data-target="#select_ImageModal">
Upload Image
</button>

<?php
    if(isset($versions)){
        $versions = json_encode($versions);
    }
 ?>

 <?php //dd($event); ?>


<form method="post" id="upload_form" method="POST" action="<?php echo e(route('upload.versionImage', $event)); ?>" autocomplete="off" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('put'); ?>

    <?php if($event): ?>
        <div class="form-group">
            <img id="img-upload"  onerror="this.src='https://via.placeholder.com/400x250'" src="
            <?php if($event['path'] != null) {
                echo url($event['path'].$event['original_name']);
            }?>">
        </div>
        <input type="hidden" value="<?php echo e($event['path'].$event['original_name']); ?>" id="image_upload" name="image_upload">
        <?php if(isset($versions)): ?>
        <input type="hidden" value="<?php echo e($versions); ?>" name="versions">
        <?php endif; ?>

    <?php else: ?>
        <div class="form-group">
            <img id="img-upload" src="">
        </div>
        <input type="hidden" value="" id="image_upload" name="image_upload">
    <?php endif; ?>
</form>


<!-- Modal -->
<div class="modal fade" id="select_ImageModal" tabindex="-1" role="dialog" aria-labelledby="select_ImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <?php echo $__env->make('admin.media2.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="modal-footer">
            <button type="button" id="close" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="select-image" class="btn btn-primary">Select</button>
        </div>
        </div>
    </div>
</div>

<?php $__env->startPush('js'); ?>

<script>
    $( "#select-image" ).click(function() {
        path = ''
        let elem = $('#select_ImageModal .table-info').parent().parent().parent().parent().parent()
        elem = $(elem).find('.fm-breadcrumb li')
        $.each( elem, function(key, value) {
            if(key != 0){
                path = path+'/'+$(value).text()
            }
        })

        name = $('#select_ImageModal .table-info .fm-content-item').text()
        if(name == ''){
            name = $('#select_ImageModal .fm-grid-item.active').attr('title')
        }

        name = name.replace(/\s/g, '')
        ext = $('#select_ImageModal .table-info td:nth-child(3)').text()
        ext = ext.replace(/\s/g, '')
        path = 'uploads'+path +'/'+name+'.'+ext
        if(name == ''){
            path = 'uploads'+path +'/'+name
        }

        $('#image_upload').val(path)
        $('#img-upload').attr('src', path);
        $(".close").click();
        $("#upload_form").submit();

    });

</script>


<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/upload/upload.blade.php ENDPATH**/ ?>
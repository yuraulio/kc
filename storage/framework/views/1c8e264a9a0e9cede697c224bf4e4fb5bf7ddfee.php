<?php //dd(); ?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".select_Svg_Modal-<?php echo e($template1); ?>">
Upload Image
</button>

<?php //dd($media); ?>

<!-- Modal -->
<div class="modal fade select_Svg_Modal-<?php echo e($template1); ?>" id=""  tabindex="-1" role="dialog" aria-labelledby="select_Svg_ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
            <div style="height: 600px;">
                <div id="<?php echo e($template1); ?>-t"></div>
            </div>
            </div>
        </div>


    </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="close_svg_modal btn btn-secondary" >Close</button>
            <button type="button" id="select-svg-<?php echo e($template1); ?>" class="btn btn-primary">Select</button>
        </div>
        </div>
    </div>
</div>

<?php $__env->startPush('js'); ?>

<script>
from = <?php echo json_encode($template1, 15, 512) ?>;

$(".close_svg_modal").click(function() {
    $('#select_Svg_Modal-'+<?php echo json_encode($template1, 15, 512) ?>).modal('hide')
})

    $( "#select-svg-"+<?php echo json_encode($template1, 15, 512) ?> ).click(function() {
        path = ''



        $.each( $('.select_Svg_Modal-'+<?php echo json_encode($template1, 15, 512) ?> + ' .fm-breadcrumb li'), function(key, value) {
            if(key != 0){
                path = path+'/'+$(value).text()
            }
        })

        name = $('.select_Svg_Modal-'+<?php echo json_encode($template1, 15, 512) ?>+ ' .table-info .fm-content-item').text()
        name = name.replace(/\s/g, '')
        ext = $('.select_Svg_Modal-'+<?php echo json_encode($template1, 15, 512) ?>+ ' .table-info td:nth-child(3)').text()
        ext = ext.replace(/\s/g, '')
        path = '/uploads'+path +'/'+name+'.'+ext

        //alert(path)

        $('#image_svg_upload-'+<?php echo json_encode($template1, 15, 512) ?>).val(path)
        $('#img-upload-'+<?php echo json_encode($template1, 15, 512) ?>).attr('src', path)

        $('.select_Svg_Modal-'+<?php echo json_encode($template1, 15, 512) ?>).modal('hide')


    });





</script>


<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/summary/upload_svg.blade.php ENDPATH**/ ?>
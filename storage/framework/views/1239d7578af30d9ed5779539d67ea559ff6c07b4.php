
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0"><?php echo e(__('Sections')); ?></h3>
        <p class="text-sm mb-0">
                <?php echo e(__('This is an example of Section management.')); ?>

            </p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Model\User::class)): ?>
        <div class="col-4 text-right">
        <button data-toggle="modal" data-target="#sectionModal" class="btn btn-sm btn-primary"><?php echo e(__('Add section')); ?></button>
        </div>
    <?php endif; ?>
</div>

<div class="table-responsive py-4">
    <table class="table align-items-center table-flush"  id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th scope="col"><?php echo e(__('Section')); ?></th>
                <th scope="col"><?php echo e(__('Title')); ?></th>
                <th scope="col"><?php echo e(__('Description')); ?></th>
                <th scope="col"><?php echo e(__('Created at')); ?></th>
                <th scope="col"></th>
            </tr>
        </thead>

        <tbody class="section-body">
            <?php if($model->sections): ?>
                <?php $__currentLoopData = $event->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr id="section_<?php echo e($section->id); ?>">
                        <td id="section-<?php echo e($section->id); ?>"><a class="edit_btn_section1" href="#"><?php echo e($section->section); ?></td>
                        <td id="section-title-<?php echo e($section->id); ?>"><?php echo e($section->title); ?></td>
                        <td id="section-desc-<?php echo e($section->id); ?>"><?php echo e($section->description); ?></td>

                        <td><?php echo e(date_format($section->created_at, 'Y-m-d' )); ?></td>

                            <td class="text-right">

                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editSectionModal" data-id="<?php echo e($section->id); ?>" data-section="<?php echo e($section->section); ?>" data-desc="<?php echo e($section->description); ?>" data-title="<?php echo e($section->title); ?>"><?php echo e(__('Edit')); ?></a>
                                        </div>
                                    </div>

                            </td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" role="dialog" aria-labelledby="sectionModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="sectionModalLabel">Create Section</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Section information')); ?></h6>
            <div class="pl-lg-4">
            <form id="section-form" >
               <div class="form-group<?php echo e($errors->has('section') ? ' has-danger' : ''); ?>">
                  <label class="form-control-label" for="input-section"><?php echo e(__('Section')); ?></label>
                  <input type="text" name="section" id="input-section" class="form-control<?php echo e($errors->has('section') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Section')); ?>" value="<?php echo e(old('section')); ?>" required autofocus>
                  <?php echo $__env->make('alerts.feedback', ['field' => 'section'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
               <div class="form-group<?php echo e($errors->has('title-section') ? ' has-danger' : ''); ?>">
                  <label class="form-control-label" for="input-title-section"><?php echo e(__('Title')); ?></label>
                  <input type="text" name="title-section" id="input-title-section" class="form-control<?php echo e($errors->has('title-section') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title-section')); ?>" autofocus>
                  <?php echo $__env->make('alerts.feedback', ['field' => 'title-section'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
               <div class="form-group<?php echo e($errors->has('description-section') ? ' has-danger' : ''); ?>">
                  <label class="form-control-label" for="input-description-section"><?php echo e(__('Description')); ?></label>
                  <input type="text" name="description-section" id="input-description-section" class="form-control<?php echo e($errors->has('description-section') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Description')); ?>" value="<?php echo e(old('description-section')); ?>" autofocus>
                  <?php echo $__env->make('alerts.feedback', ['field' => 'description-section'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="save-section" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog" aria-labelledby="sectionModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="sectionModalLabel">Edit Section</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Section information')); ?></h6>
            <div class="pl-lg-4">
            <form id="section-form-edit">
               <div class="form-group<?php echo e($errors->has('section') ? ' has-danger' : ''); ?>">
                  <label class="form-control-label" for="edit-section"><?php echo e(__('Name')); ?></label>
                  <input type="text" name="section" id="edit-section" class="form-control<?php echo e($errors->has('section') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e(old('section')); ?>" required autofocus>
                  <?php echo $__env->make('alerts.feedback', ['field' => 'section'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
               <div class="form-group<?php echo e($errors->has('title-section') ? ' has-danger' : ''); ?>">
                  <label class="form-control-label" for="edit-title-section"><?php echo e(__('Title')); ?></label>
                  <input type="text" name="title-section" id="edit-title-section" class="form-control<?php echo e($errors->has('title-section') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title-section')); ?>" autofocus>
                  <?php echo $__env->make('alerts.feedback', ['field' => 'title-section'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
               <div class="form-group<?php echo e($errors->has('description-section') ? ' has-danger' : ''); ?>">
                  <label class="form-control-label" for="edit-description-section"><?php echo e(__('Section')); ?></label>
                  <input type="text" name="description-section" id="edit-description-section" class="form-control<?php echo e($errors->has('description-section') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Section')); ?>" value="<?php echo e(old('description-section')); ?>" autofocus>
                  <?php echo $__env->make('alerts.feedback', ['field' => 'description-section'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
               <input type="text" id="section-id"  value="" hidden>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="edit-section-btn" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>


<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>

    <script>

    $(document).on('click',".edit_btn_section1",function(){
        //alert('asd')
        $(this).parent().parent().find('a')[2].click()
        //$(this).parent().parent().find('.dropdown-item').click()
    })

        // $(document).on('shown.bs.modal', '#editSectionModal',function(e) {
        //     //e.preventDefault()
        //     var link  = e.relatedTarget,
        //             modal    = $(this),
        //         id = e.relatedTarget.dataset.id
        //         //name = e.relatedTarget.dataset.name,
        //         //description =e.relatedTarget.dataset.description;
        //         name = $("#name-"+id).text();
        //         description = $("#desc-"+id).text();

        //         modal.find("#sectionModalLabel").val(name)

        //     modal.find("#edit-name").val(name);
        //     modal.find("#edit-description1").val(description);
        //     modal.find("#section-id").val(id)

        // });
    </script>
    <script>
        $(document).on('click',"#save-section",function(){

            let modelType = "<?php echo e(addslashes ( get_class($model) )); ?>";
            let modelId = "<?php echo e($model->id); ?>";

                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '<?php echo e(route("section.store")); ?>',
                    data: {'section':$('#input-section').val(), 'title':$('#input-title-section').val(),'description':$('#input-description-section').val(),'model_type':modelType,'model_id':modelId},
                    success: function (data) {
                //console.log(data);
                let section = data.section;
                let newSection =
                `<tr id="section_`+section['id']+`">` +
                `<td id="section-` + section['id'] +`"><a class="edit_btn_section1" href="#">` + section['section'] + `</td>` +
                `<td id="section-title-` + section['id'] +`">` + section['title'] + `</td>` +
                `<td id="section-desc-`+section['id']+`">` + section['description'] + `</td>` +
                `<td>` + section['created_at'] + `</td>` +

            `<td class="text-right">
                        <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" data-toggle="modal" data-target="#editSectionModal" data-id="` + section['id'] + `" data-section="`+section['section'] + `" data-title-section="`+section['title'] +`" data-description="`+ section['description'] + `"><?php echo e(__('Edit')); ?></a>

                        </div>
                        </div>
                    </td>

                </tr>`;


                $(".section-body").append(newSection);
                $(".close-modal").click();
                $("#success-message p").html(data.success);
                $("#success-message").show();
                $('#section-form').trigger('reset');
                    }
                })
        });
    </script>


    <script>
    $(document).on('click',"#edit-section-btn",function(){

        $sectionId = $("#section-id").val()
        $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'put',
                url: '/admin/section/' + $sectionId,
                data: {'section':$('#edit-section').val(),'title':$('#edit-title-section').val(),'description':$('#edit-description-section').val()},
                success: function (data) {

                    let section = data.section;

                    $("#section-"+section['id']).html(`<a class="edit_btn_section1" href="#">`+section['section'])
                    $("#section-title-"+section['id']).html(section['title'])
                    $("#section-desc-"+section['id']).html(section['description'])
                    $('#section-form-edit').trigger('reset');
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

    $(document).on('shown.bs.modal', '#editSectionModal',function(e) {
        e.preventDefault()


        var link  = e.relatedTarget,
            modal    = $(this),
            id = e.relatedTarget.dataset.id

            section = $("#section-"+id).text(),

            title = $("#section-title-"+id).text();
            description = $("#section-desc-"+id).text();

            modal.find("#sectionModalLabel").val(title)

        modal.find("#edit-section").val(section);
        modal.find("#edit-title-section").val(title);
        modal.find("#edit-description-section").val(description);
        modal.find("#section-id").val(id)

    });





    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/section/index.blade.php ENDPATH**/ ?>
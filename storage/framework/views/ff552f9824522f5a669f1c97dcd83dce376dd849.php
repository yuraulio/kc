

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('Examples')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('menu.index')); ?>"><?php echo e(__('Menus Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('List')); ?></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e($name); ?></li>
        <?php if (isset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd)): ?>
<?php $component = $__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd; ?>
<?php unset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    <?php if (isset($__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5)): ?>
<?php $component = $__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5; ?>
<?php unset($__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                <div class="col-12 mt-2">
                        <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0"><?php echo e(__('Menus')); ?></h3>
                                <p class="text-sm mb-0">
                                        <?php echo e(__('This is an example of Menu management.')); ?>

                                    </p>
                            </div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Model\User::class)): ?>
                                <div class="col-4 text-right">
                                    <button data-toggle="modal" data-target="#menuModal" class="btn btn-sm btn-primary"><?php echo e(__('Add item')); ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic13">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col"><?php echo e(__('Name')); ?></th>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
                                        <th scope="col"></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody id="menu-body">
                            <?php if($result): ?>
                                <?php $__currentLoopData = $result[$name]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr id="itemMenu_<?php echo e($menu['id']); ?>">
                                        <td><?php echo e($menu['data']['name']); ?></td>
                                        
                                        <?php $id = $menu['data']['id']; ?>
                                        
					                        <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        
                                                        <a class="dropdown-item" id="remove_item" data-item-id="<?php echo e($menu['id']); ?>"><?php echo e(__('Delete')); ?></a>

                                                        
                                                    </div>
                                                </div>
                                            </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel"><?php echo e($name); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="optionFormControlMenu">Item</label>
                    <select class="form-control" id="menuItem">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="button" id="save-btn" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>

        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select/js/dataTables.select.min.js"></script>


    <script>
        $( "#assignButton" ).on( "click", function(e) {
            const eventId = $(this).data("event-id");

            $.ajax({
               type:'POST',
               url:'/getmsg',
               data:'_token = <?php echo csrf_token() ?>',
               success:function(data) {
                  $("#msg").html(data.msg);
               }
            });

        });


        $(document).on('shown.bs.modal', '#menuModal',function(e) {
            $('#menuItem').empty()
            let menu_name = "<?php echo e($name); ?>";

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/menu/fetchAllMenu',
                data:{'name': menu_name},
                success: function (data) {
                    let categories = data.data.categories
                    let types = data.data.types
                    let deliveries = data.data.deliveries

                    $.each( categories, function( key, value ) {
                    row =`
                    <option value="Category-${value.id}">${value.name}</option>
                    `
                    $('#menuItem').append(row)

                    });

                    $.each( types, function( key, value ) {
                       //console.log(key + ':' + value.title)
                    row =`
                    <option value="Type-${value.id}">${value.name}</option>
                    `
                    $('#menuItem').append(row)

                    });

                    $.each( deliveries, function( key, value ) {
                       //console.log(key + ':' + value.title)
                    row =`
                    <option value="Delivery-${value.id}">${value.name}</option>
                    `
                    $('#menuItem').append(row)

                    });
                }
            });
        })

        $(document).on('click',"#save-btn",function(){

            let menu_name = "<?php echo e($name); ?>";


        var selected_option = $('#menuItem option:selected');

        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '<?php echo e(route("menu.store_item")); ?>',
            data: {'menu':$(selected_option).val(),'name':menu_name},
            success: function (data) {
            let item = data.data.find_item;
            let menu = data.data.menu;
            let newItem =
            `<tr id=itemMenu_`+menu.id+`>` +
            `<td>`+item.name+`</td>`+
            `<td class="text-right">
                <div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" id="remove_item" data-item-id="`+menu.id+`"><?php echo e(__('Delete')); ?></a>

                    </div>
                </div>
            </td>

            </tr>`;


            $("#menu-body").append(newItem);
            $(".close-modal").click();
            $("#success-message p").html(data.success);
            $("#success-message").show();
            // $('#newRow').empty()
            // $('#newRowEdit').empty()
            // $('#ticket-form').trigger('reset');
            $('#menuItem').empty()
                },
                error: function() {
                    //console.log(data);
                }
            });



        })

        $(document).on('click', '#remove_item',function(e) {
            let id = $(this).data('item-id')

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '<?php echo e(route("menu.remove_item")); ?>',
                data: {'item_id':id},
                success: function (data) {


                $(`#itemMenu_${data.data}`).remove()

                }
            });
            })

      </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('Menu Management'),
    'parentSection' => 'laravel',
    'elementName' => 'menu-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/menu/edit.blade.php ENDPATH**/ ?>
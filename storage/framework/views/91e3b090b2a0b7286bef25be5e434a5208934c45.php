

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>
            <?php $__env->slot('filter'); ?>
                <!-- <a href="#" class="btn btn-sm btn-neutral"><?php echo e(__('Filters')); ?></a> -->
                <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><?php echo e(__('Filters')); ?></a>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('abandoned.index')); ?>"><?php echo e(__('Abandoned Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('List')); ?></li>
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
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0"><?php echo e(__('Abandoned')); ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <div class="table-responsive py-4">

                        <div class="collapse" id="collapseExample">
                            <div class="container">
                                <div class="row">
                                    <div class="col-4" id="filter_col1" data-column="1">
                                        <label>Event</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="event" class="column_filter" id="col1_filter">
                                            <option selected value> -- All -- </option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <a href="abandoned/exportcsv" style="margin-left:1.2rem;" class="btn btn-primary btn-sm">Export csv</a>
                        <hr>
                        <table class="table align-items-center table-flush"  id="abandoned_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col"><?php echo e(__('User')); ?></th>
                                    <th scope="col"><?php echo e(__('Event')); ?></th>
                                    <th scope="col"><?php echo e(__('Ticket')); ?></th>
                                    <th scope="col"><?php echo e(__('Qty')); ?></th>
                                    <th scope="col"><?php echo e(__('Amount')); ?></th>
                                    <th scope="col"><?php echo e(__('Dates')); ?></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php //dd($abcart); ?>
                                <?php if(isset($list)): ?>
                                    <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_id => $ucart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php //dd($abcart); ?>
                                        <?php if(isset($abcart[$user_id]->user) && isset($tickets[$ucart->id])): ?>
                                        <?php
                                            $evdate = 'No Date';
                                            if(isset($events[$ucart->options['event']]['customFields'])) {
                                                foreach ($events[$ucart->options['event']]['customFields'] as $ckey => $cvalue) {
                                                    if ($cvalue->name == 'simple_text' && $cvalue->priority == 0) {
                                                        $evdate = $cvalue->value;
                                                        break;
                                                    }
                                                }
                                            }
                                        ?>

                                        <tr>
                                            <?php //dd($events[$ucart->options['event']]['title']); ?>
                                            <td><?php if($abcart[$user_id]['user']->first() != null): ?><a href="mailto:<?php echo e($abcart[$user_id]['user']->first()['email']); ?>"><?php echo e($abcart[$user_id]['user']->first()['email']); ?></a><br /><?php echo e($abcart[$user_id]['user']->first()['firstname']); ?> <?php echo e($abcart[$user_id]['user']->first()['lastname']); ?><br /><a target="_blank" href="admin/student/<?php echo e($user_id); ?>"><i class="fa fa-external-link"></i></a> <?php endif; ?></td>
                                            <td class="text-center"><?php echo e($events[$ucart->options['event']]['title']); ?></td>
                                            <td class="text-center"><?php echo e($tickets[$ucart->id]->title); ?></td>
                                            <td class="text-center"><?php echo e($ucart->qty); ?></td>
                                            <td class="text-right">&euro;<?php echo e($ucart->qty*$ucart->price); ?></td>

                                            <td class="td_categories text-right"><?php if(isset($abcart[$user_id]->created_at) && $abcart[$user_id]->created_at != ''): ?> C:<?php echo e($abcart[$user_id]->created_at->format('d/m/Y H:i')); ?> <?php endif; ?> <br />U:<?php echo e($abcart[$user_id]->updated_at->format('d/m/Y H:i')); ?></td>
                                            


                                            <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                    <form action="<?php echo e(route('abandoned.remove', $abcart[$user_id]->identifier)); ?>" method="post">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('post'); ?>

                                                        <button type="button" class="dropdown-item" onclick="confirm('<?php echo e(__("Are you sure you want to delete this item?")); ?>') ? this.parentElement.submit() : ''">
                                                            <?php echo e(__('Delete')); ?>

                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                            </tbody>
                        </table>
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
        function fillSelectedBox(){
            events = table.column(1).data().unique().sort()
            $.each(events, function(key, value){
                $('#col1_filter').append('<option value="'+value+'">'+value+'</option>')
            })
        }

        function filterColumn ( i ) {
            table.column( i ).search($('#col'+i+'_filter').val()).draw();
        }

        // DataTables initialisation
        var table = $('#abandoned_table').DataTable({
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        $(document).ready(function() {
            fillSelectedBox()

            $('select.column_filter').on('change', function () {
                filterColumn( $(this).parents('div').attr('data-column') );

            } );

        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('Abandoned Management'),
    'parentSection' => 'laravel',
    'elementName' => 'abandoned-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/abandoned/index.blade.php ENDPATH**/ ?>
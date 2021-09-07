

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

            <li class="breadcrumb-item"><a href="<?php echo e(route('subscriptions.index')); ?>"><?php echo e(__('Subscriptions List')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('List')); ?></li>
        <?php if (isset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd)): ?>
<?php $component = $__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd; ?>
<?php unset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
        <?php echo $__env->make('admin.subscription.layouts.cards', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                <h3 class="mb-0"><?php echo e(__('Subscriptions')); ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <?php //dd($transactions[0]); ?>

                    <div class="table-responsive py-4">

                    <div class="collapse" id="collapseExample">
                        <div class="container">
                            <div class="row">
                                <div class="col" id="filter_col1" data-column="3">
                                    <label>Event</label>
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="event" class="column_filter" id="col3_filter">
                                        <option selected value> -- All -- </option>
                                    </select>
                                </div>
                                <div class="col" id="filter_col4" data-column="4">
                                    <label>Status</label>
                                    <select data-toggle="select" data-live-search="true" class="column_filter" id="col4_filter" placeholder="Status">
                                        <option selected value> -- All -- </option>
                                        <option value="1"> ACTIVE </option>
                                        <option value="0"> INACTIVE </option>
                                    </select>
                                </div>




                                <div id="sub_datePicker" class="input-daterange datepicker">
                                    <div class="col">
                                    <label>From</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                                </div>
                                                <input class="form-control select2-css" id="min" placeholder="Start date" type="text" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                    <label>To</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                                </div>
                                                <input class="form-control select2-css" placeholder="End date" id="max" type="text" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>






                            </div>
                        </div>
                    </div>

                    

                        <table class="table align-items-center table-flush"  id="subscriptions_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col"><?php echo e(__('Id')); ?></th>
                                    <th scope="col"><?php echo e(__('Student')); ?></th>
                                    <th scope="col"><?php echo e(__('Plan')); ?></th>
                                    <th scope="col"><?php echo e(__('Event Name')); ?></th>
                                    <th scope="col"><?php echo e(__('Status')); ?></th>
                                    
                                    <th scope="col"><?php echo e(__('Sub end at')); ?></th>
                                    <th scope="col"><?php echo e(__('Amount')); ?></th>
                                    <th class="d-none" scope="col"><?php echo e(__('Create Date')); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th><?php echo e(__('Id')); ?></th>
                                    <th><?php echo e(__('Student')); ?></th>
                                    <th><?php echo e(__('Plan')); ?></th>
                                    <th><?php echo e(__('Event Name')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Sub end at')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th class="d-none"><?php echo e(__('Create Date')); ?></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $__currentLoopData = $new_sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php echo e($item['id']); ?>

                                        </td>
                                        <td>
                                            <?php
                                                $user = $item['user'][0];
                                            ?>
                                            <?php echo e($user['firstname']); ?> <?php echo e($user['lastname']); ?>

                                        </td>
                                        <td><?php echo e($item['subscription'][0]['plan_name']); ?></td>
                                        <td><?php echo e($item['subscription'][0]['event'][0]['title']); ?></td>

                                        <td><?php echo e($item['status']); ?></td>



                                        <td><?php echo e($item['ends_at']); ?></td>
                                        <td><?= '€'.number_format(intval($item['total_amount']), 2, '.', ''); ?></td>
                                        <td class="d-none"><?= date_format(date_create($item['created_at']),'m/d/Y'); ?></td>


                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables-datetime/datetime.min.css">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables-datetime/datetime.min.js"></script>
    <script>

    var minDate = null, maxDate = null;

    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = minDate;
            var max = maxDate;


            var date = new Date(data[7]);

            date = moment(date).format('MM/DD/YYYY')

            if (
                ( min === null && max === null ) ||
                ( min === null && date <= max ) ||
                ( min <= date   && max === null ) ||
                ( min <= date   && date <= max )
            ) {
                return true;
            }
            return false;
        }
    );



        function initStats(){
            amount = $('#subscriptions_table').DataTable().column( 6 ).data();
            let sum = 0;


            $.each(amount, function(key, value) {
                value = value.replace("€", "")
                sum = sum + parseInt(value)

            })



            $('#total').text('€'+sum)

        }

        function fillSelectedBox(){
            events = table.column(3).data().unique().sort()
            $.each(events, function(key, value){
                $('#col3_filter').append('<option value="'+value+'">'+value+'</option>')
            })
        }

        function filterColumn ( i ) {
            if(i == 4){ //Status filter
                if($('#col'+i+'_filter').val() != ''){
                    status = $('#col'+i+'_filter').val()
                }else{
                    status = ''
                }
                value = status

            }else if(i == 3){ // Event filter
                if($('#col'+i+'_filter').val() != ''){
                    value = $('#col'+i+'_filter').val()
                }else{
                    value = ''
                }

            }
            table.column( i ).search(value).draw();
        }


        // DataTables initialisation
        var table = $('#subscriptions_table').DataTable({
            "order": [[ 4, "desc" ]],
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });
        initStats()

        let status = '';

        $(document).ready(function() {
            fillSelectedBox()

            $('select.column_filter').on('change', function () {
                filterColumn( $(this).parents('div').attr('data-column') );

            } );


            // Refilter the table
            $('#min, #max').on('change', function () {
                min = new Date($('#min').val());
                max = new Date($('#max').val());
                minDate = moment(min).format('MM/DD/YYYY')
                maxDate = moment(max).format('MM/DD/YYYY')


                table.draw();
            });






        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('Subscription Management'),
    'parentSection' => 'laravel',
    'elementName' => 'subscriptions-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/subscription/subscription_list.blade.php ENDPATH**/ ?>
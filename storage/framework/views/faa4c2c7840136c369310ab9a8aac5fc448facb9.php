<div class="card-header">
    <div class="row align-items-center">
        <div class="col-8">
            <h3 class="mb-0"><?php echo e(__('Students')); ?></h3>
        </div>
    </div>
</div>

<div class="col-12 mt-2">
    <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

    <table class="table align-items-center table-flush"  id="datatable-basic-students1">
        <thead class="thead-light">
            <tr>
                <th scope="col"><?php echo e(__('Name')); ?></th>
                <th scope="col"><?php echo e(__('Lastname')); ?></th>
                <th scope="col"><?php echo e(__('Email')); ?></th>
                <th scope="col"><?php echo e(__('Mobile')); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php //dd($allTopicsByCategory); ?>
            <?php $__currentLoopData = $eventUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tr>
                    <td><a target="_blank" href="<?php echo e(route('user.edit', $user['id'])); ?>"><?php echo e($user['firstname']); ?></a></td>
                    <td><?php echo e($user['lastname']); ?></td>
                    <td><a href="mailto:<?php echo e($user['email']); ?>"><?php echo e($user['email']); ?></a> </td>
                    <td><?php echo e($user['mobile']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>




<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js" integrity="sha512-xQBQYt9UcgblF6aCMrwU1NkVA7HCXaSN2oq0so80KO+y68M+n64FOcqgav4igHe6D5ObBLIf68DWv+gfBowczg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select/js/dataTables.select.min.js"></script>


    <script>

        $(document).ready(function() {
            var table = $('#datatable-basic-students1').DataTable({
                buttons: ['copy'],

                language: {
                    paginate: {
                    next: '&#187;', // or '→'
                    previous: '&#171;' // or '←'
                    }
                },
            });

            table.buttons().container()
        .appendTo('#datatable-basic-students1_wrapper .col-md-6:eq(0)');

        } );
    </script>


<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/event/students.blade.php ENDPATH**/ ?>
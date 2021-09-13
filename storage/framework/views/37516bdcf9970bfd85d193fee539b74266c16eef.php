<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h3 class="mb-0"><?php echo e(__('Categories')); ?></h3>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Model\Role::class)): ?>
                <div class="col-4 text-right">
                    <a href="<?php echo e(route('category.create')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Add category')); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12 mt-2">
        <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="table-responsive py-4">
        <table class="table table-flush"  id="datatable-basic29">
            <thead class="thead-light">
                <tr>
                    <th scope="col"><?php echo e(__('Name')); ?></th>
                    <th scope="col"><?php echo e(__('Parent')); ?></th>
                    <th scope="col"><?php echo e(__('Hours')); ?></th>
                    <th scope="col"><?php echo e(__('Dropbox')); ?></th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
                        <th scope="col"></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php //dd($categories); ?>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><a href="<?php echo e(route('category.edit', $category)); ?>"><?php echo e($category->name); ?></a></td>
                        <td><?php echo e($category->parent); ?></td>
                        <td><?php echo e($category->hours); ?></td>
                        <td>
                        <?php if(count($category->dropbox) != 0): ?>
                            <i class="ni ni-check-bold"></i>
                        <?php endif; ?>
                        </td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
                            <td class="text-right">
                              
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="<?php echo e(route('category.edit', $category)); ?>"><?php echo e(__('Edit')); ?></a>
                                            
                                            <form action="<?php echo e(route('category.destroy', $category)); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('delete'); ?>

                                                <button type="button" class="dropdown-item" onclick="confirm('<?php echo e(__("Are you sure you want to delete this category?")); ?>') ? this.parentElement.submit() : ''">
                                                    <?php echo e(__('Delete')); ?>

                                                </button>
                                            </form>
                                            
                                        </div>

                                    </div>
                               
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/global_settings/categories/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.headers.guest', ['infoLogin' => 1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">

                    <div class="card-body px-lg-5 py-lg-5">
                        <form role="form" method="POST" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="form-group<?php echo e($errors->has('email') ? ' has-danger' : ''); ?> mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Email')); ?>" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                                </div>
                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group<?php echo e($errors->has('password') ? ' has-danger' : ''); ?>">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" placeholder="<?php echo e(__('Password')); ?>" type="password" value="" required>
                                </div>
                                <?php if($errors->has('password')): ?>
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" name="remember" id="customCheckLogin" type="checkbox" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="customCheckLogin">
                                    <span class="text-muted"><?php echo e(__('Remember me')); ?></span>
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4"><?php echo e(__('Sign in')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['class' => 'bg-default'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/auth/login.blade.php ENDPATH**/ ?>
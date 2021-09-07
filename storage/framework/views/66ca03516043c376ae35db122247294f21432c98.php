

<?php $__env->startSection('content'); ?>
Hi,
<br /><br />
There was a request to activate your account.
<br />
If you did not make this request, just ignore this email. Otherwise, please click the button bellow to activate your account.
<br />
<br />
<a href="<?php echo e(URL::to("myaccount/activate/{$code}")); ?>">Activate Account </a><br /><br />
<br />

<?php echo $__env->make('emails.partials.the_team', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/activation/emails/activate.blade.php ENDPATH**/ ?>
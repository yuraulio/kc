

<?php $__env->startSection('content'); ?>

Hi,
<br /><br />
There was a request to change your password.
<br />
If you did not make this request, just ignore this email. Otherwise, please click the button bellow to change your password.
<br /><br />
<a href="<?php echo e(URL::to("myaccount/reset/{$user->id}/{$code}")); ?>">Change Password</a><br /><br />

<?php echo $__env->make('emails.partials.the_team', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/activation/emails/student-reminder.blade.php ENDPATH**/ ?>
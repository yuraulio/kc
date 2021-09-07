<?php

?>


<?php $__env->startSection('content'); ?>

<p>
Dear <?php echo e($firstName); ?>,<br />
<p>
<br/>

We remind you that your access to the <?php echo e($eventTitle); ?> is completed in <?php echo e($expirationDate); ?>. 
<br />
<br />
Thank you,

<br />
<br />
<span style="color:#006aa9;">KNOWCRUNCH </span> | LEARN. TRANSFORM. THRIVE <br />
We respect your data. Read our <a href="https://knowcrunch.com/data-privacy-policy">data privacy policy</a>



<br />

<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/emails/admin/remind_elearning.blade.php ENDPATH**/ ?>
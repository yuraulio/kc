<?php
    /**
     * Inform admin about a new student registration
     * @user : the new registration user (object)
     * @account : the account (array), has the following linked arrays
     * @account['default_store']
     * @account['users']
     */

    //dd($trans);  txId:  $res['txId']

//$extrainfo = [$tickettypedrop, $tickettype, $eventname, $eventdate];
    //$helperdetails[$user->email] = ['kcid' => $user->kc_id, 'deid' => $user->partner_id];
?>

<?php $__env->startSection('content'); ?>

<p>
Dear <?php echo e($firstName); ?>,<br />
<p>
<br/>

<p>We hope your watching of our E-learning program is progressing successfully. We are here to remind you of some basic information:</p>

<ul>
	<li>
	<p>Your exams are activated by watching at least 80% of our videos</p>
	</li>
</ul>

<ul>
	<li>
	<p>KnowCrunch instructors support are always available for your questions and queries in our Facebook group in the link <a href="https://www.facebook.com/groups/elearningdigital/">https://www.facebook.com/groups/elearningdigital/</a></p>
	</li>
</ul>

<br />
<p>Always at your disposal for any help,</p>
<br />
<span style="color:#006aa9;">KNOWCRUNCH </span> | LEARN. TRANSFORM. THRIVE <br />
We respect your data. Read our <a href="https://knowcrunch.com/data-privacy-policy">data privacy policy</a>



<br />





<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/emails/student/elearning_half_period.blade.php ENDPATH**/ ?>
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

<p></p>
We gladly inform you that your exams on the <?php echo e($eventTitle); ?> have been activated!
You can find the Exams button in the <?php echo e($eventTitle); ?> box, under the MY COURSES tab. A green box writing “TAKE EXAMS” will appear on the far right
<br/>

<p>Some useful information about your exams:</p>

<ul>
	<li>
	<p>Your exams are activated by watching at least 80% of our videos</p>
	</li>

	<li>
	<p> Do not start your exams if you don’t feel ready</p>
	</li>

	<li>
	<p>You have 2 hours to complete the test </p>
	</li>

	<li>
	<p>The minimum score for succeeding is 70%</p>
	</li>

	<li>
	<p>The exams result will reveal after finishing the exams </p>
	</li>

	<li>
	<p>There are only multiple choice questions, with 4 possible answers in each, but only one is the correct answer</p>
	</li>
</ul>

<br />
<p>Good luck!</p>
<br />
<span style="color:#006aa9;">KNOWCRUNCH </span> | LEARN. TRANSFORM. THRIVE <br />
We respect your data. Read our <a href="https://knowcrunch.com/data-privacy-policy">data privacy policy</a>



<br />





<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/emails/student/exam_activate.blade.php ENDPATH**/ ?>
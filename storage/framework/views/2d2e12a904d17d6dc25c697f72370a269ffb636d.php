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

<?php $res = $trans->payment_response;
    if ($res) {
        $res = json_decode($res, true);
    }
?>

<h1>Thank you <?php echo e($user['name']); ?></h1>
<p>
Your registration has been completed.<br /><br />
You are one of the privileged attendants of <strong><?php echo e($extrainfo[2]); ?></strong> which takes place: <strong><?php echo e($extrainfo[3]); ?></strong>.<br /><br /> Get ready for a wonderful experience.<br />

<p>
Registration information: <br /><br />



 Student: <strong><?php echo e($user['name']); ?></strong><br />
 <?php if(isset($helperdetails[$user['email']]) && $helperdetails[$user['email']]['kcid'] != ''): ?>
 Student ID: <strong><?php echo e($helperdetails[$user['email']]['kcid']); ?></strong><br />
 <?php endif; ?>
 Ticket Type: <strong><?php echo e($extrainfo[1]); ?></strong><br />
 Ticket Price: <?php if($trans->total_amount == 0): ?> <strong> Free </strong> <?php else: ?> <strong><?php echo e($trans->total_amount); ?></strong><?php endif; ?><br />
 <?php if(isset($res['paymentRef']) && $res['paymentRef'] != ''): ?>
 Bank Payment Reference: <strong><?php echo e($res['paymentRef']); ?></strong><br />
 <?php endif; ?>
</p>

<p>
Meanwhile, join a great Facebook community for Greek Digital &amp; Social Media Professionals: <a href="https://www.facebook.com/groups/socialmediagreece/" target="_blank">the Digital &amp; Social Media Nation.</a><br />
</p>

<p>
See you in our course!
</p>

<br />

<?php echo $__env->make('emails.partials.the_team', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/emails/admin/info_new_registration.blade.php ENDPATH**/ ?>
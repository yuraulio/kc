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
ENROLLMENT SUCCESSFUL<br />
You are one of the privileged students of  <strong><?php echo e($extrainfo[2]); ?></strong> and you have <strong><?php echo e($extrainfo[3]); ?></strong>.
<p>
<br/>
<br />
<br />
<br />
PARTICIPANT <br />
Participant: <strong><?php echo e($user['name']); ?></strong><br />
Your login: <strong><?php echo e($user['email']); ?> </strong> <br />
<?php if(isset($helperdetails[$user['email']]) && $helperdetails[$user['email']]['kcid'] != ''): ?>
 Student ID: <strong><?php echo e($helperdetails[$user['email']]['kcid']); ?></strong><br />
 <?php endif; ?>

<br />

SUBSCRIPTION<br/>
 Ticket Type: <strong><?php echo e($extrainfo[1]); ?></strong><br />
 Ticket Price: <?php if($trans->total_amount == 0): ?> Free <?php else: ?> <?php echo e(round($trans->total_amount,2)); ?></strong><?php endif; ?><br />
 <?php if($extrainfo[8]): ?> Expiration date: <strong><?php echo e(date('d-m-Y', strtotime($extrainfo[8]))); ?></strong><br /><?php endif; ?>


</p>
<br/>
<p>
USEFULL LINKS <br/>
Start watching: <a href="https://www.knowcrunch.com/<?php echo e($eventslug); ?>" target="_blank"><?php echo e($extrainfo[2]); ?></a><br />
E-Learning support group: <a href="<?php echo e($extrainfo[7]); ?>" target="_blank"><?php echo e($extrainfo[2]); ?>-Facebook</a><br />
</p>

<br />

<?php echo $__env->make('emails.partials.the_team', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/emails/admin/info_new_registration_elearning.blade.php ENDPATH**/ ?>
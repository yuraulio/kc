<?php
    /**
     * Inform admin about a new company registration
     * @user : the new registration user (object)
     * @account : the account (array), has the following linked arrays
     * @account['default_store']
     * @account['users']
     */
?>

<?php $__env->startSection('content'); ?>
<?php $res = $trans->payment_response;
	if ($res) {
		$res = json_decode($res, true);
	}
 ?>

<h1>
New registration has been received
</h1>
<?php
	switch ($extrainfo[0]) {
		case 1:
			$type = 'UNEMPLOYED';
			break;
		case 2:
			$type = 'STUDENT';
			break;
		case 3:
			$type = 'KNOWCRUNCH ALUMNI';
			break;
		case 4:
			$type = 'DEREE ALUMNI';
			break;
		case 5:
			$type = 'GROUP OF 2+';
			break;


		default:
			$type = 'REGULAR';
			break;
	}

//check ticket type, if dropdown special seat show speciality otherwise show ticket name
if ($extrainfo[4] == 1) {

    $tickettype = $type;
}
else {

    $tickettype = $extrainfo[1];
}


if(isset($helperdetails[$user['email']])) {

    $did = $helperdetails[$user['email']]['deid'];
    $mob = $helperdetails[$user['email']]['mobile'];
    $com = $helperdetails[$user['email']]['company'];
    $job = $helperdetails[$user['email']]['jobtitle'];
    //$stid = $helperdetails[$user['email']]['stid']; (user given ticket type id)
    $dereeid = $did; //substr($did, 0, -2);
}
else {

    $did = '-';
    //$stid = '-';
    $dereeid = '-';
    $mob = '-';
    $com = '-';
    $job = '-';
}



?>

<?php if(isset($trans->status_history[0]['pay_seats_data']['studentId'])){

	$stId = $trans->status_history[0]['pay_seats_data']['studentId'][0];
	
}else{
	
	$stId = '-';
}
?>

<p>
<strong>EVENT NAME:</strong> <?php echo e($extrainfo[2]); ?><br /><br />
<strong>EVENT DATE:</strong> <?php echo e($extrainfo[3]); ?><br /><br />
<strong><?php echo e($type); ?> ID:</strong> <?php echo e($stId); ?><br /><br />
<strong>FULL NAME:</strong> <?php echo e($user['name']); ?><br /><br />
<strong>EMAIL:</strong> <?php echo e($user['email']); ?><br /><br />
<strong>MOBILE PHONE:</strong> <?php echo e($mob); ?><br /><br />
<strong>JOB TITLE:</strong> <?php echo e($job); ?><br /><br />
<strong>COMPANY NAME:</strong> <?php echo e($com); ?><br /><br />

<strong>TICKET TYPE:</strong> <?php echo e($tickettype); ?> <br /><br />
<strong>TICKET PRICE:</strong> <?php if($trans->total_amount == 0): ?> Free <?php else: ?> <?php echo e($trans->total_amount); ?> <?php endif; ?><br /><br />

<strong>INVOICE:</strong> <?php echo e($extrainfo[5]); ?><br /><br />
<strong>CITY:</strong> <?php echo e($extrainfo[6]); ?><br /><br />

<?php if(isset($trans->coupon_code) && $trans->coupon_code != ''): ?> <strong>COUPON CODE:</strong> <?php echo e($trans->coupon_code); ?><br /><br /><?php endif; ?>
<?php if($user['id']): ?><a target="_blank" href="http://www.knowcrunch.com/admin/user/<?php echo e($user['id']); ?>/edit">Check the registration details online</a>.<br /><br /><?php endif; ?>
<!--<a target="_blank" href="https://kclioncode.j.scaleforce.net/admin/transaction/edit/<?php echo e($trans->id); ?>">Check the registration details online</a>.<br /><br />-->

</p>

<?php echo $__env->make('emails.partials.the_team', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/emails/admin/admin_info_new_registration.blade.php ENDPATH**/ ?>
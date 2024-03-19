<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Submitting data to AlphaBank Servers</title>
	</head>
	<body>
		<div style="display: none;">

			<?php //if ($payment_method_details['status'] == 1) :
				if (config('app.PAYMENT_PRODUCTION') != false) :
			?>
			<form method="post" name="paymentForm" id="paymentForm" action="<?php echo $payment_options['productionURL']; ?>">
			<?php else : ?>
			<form method="post" name="paymentForm" id="paymentForm" action="<?php echo $payment_options['developmentURL']; ?>">
			<?php endif; ?>
				<input type="hidden" name="mid" value="<?php echo $sbt_data['mid']; ?>"/>
				<input type="hidden" name="currency" value="<?php echo $sbt_data['currency']; ?>"/>
				<input type="hidden" name="lang" value="<?php echo $sbt_data['lang']; ?>"/>
				<input type="hidden" name="orderid" value="<?php echo $sbt_data['orderid']; ?>"/>
				<input type="hidden" name="orderDesc" value="<?php echo $sbt_data['orderDesc']; ?>"/>
				<input type="hidden" name="orderAmount" value="<?php echo $sbt_data['orderAmount']; ?>"/>
				<input type="hidden" name="billCountry" value="<?php echo $sbt_data['billCountry']; ?>"/>
				<input type="hidden" name="billState" value="<?php echo $sbt_data['billState']; ?>"/>
				<input type="hidden" name="billZip" value="<?php echo $sbt_data['billZip']; ?>"/>
				<input type="hidden" name="billCity" value="<?php echo $sbt_data['billCity']; ?>"/>
				<input type="hidden" name="billAddress" value="<?php echo $sbt_data['billAddress']; ?>"/>
				<input type="hidden" name="confirmUrl" value="<?php echo $sbt_data['confirmUrl']; ?>"/>
				<input type="hidden" name="cancelUrl" value="<?php echo $sbt_data['cancelUrl']; ?>"/>
				<input type="hidden" name="var2" value="<?php echo $sbt_data['var2']; ?>"/>
				<input type="hidden" name="var3" value="<?php echo $sbt_data['var3']; ?>"/>
				<?php if (isset($sbt_data['extInstallmentperiod']) && $sbt_data['extInstallmentperiod'] > 1) : ?>
				<input type="hidden" name="extInstallmentoffset" value="0"/>
				<input type="hidden" name="extInstallmentperiod" value="<?php echo $sbt_data['extInstallmentperiod']; ?>"/>
				<?php endif; ?>
				<input type="hidden" name="digest" value="<?php echo $sbt_data['digest']; ?>"/>
			</form>
		</div>
		<script>
			document.forms["paymentForm"].submit();
		</script>
	</body>
</html>

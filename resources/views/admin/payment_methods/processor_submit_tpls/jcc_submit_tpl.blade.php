<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Submitting data to JCC Servers</title>
	</head>
	<body>
		<div style="display: none;">
			<?php if ($payment_options['status'] == 1) : ?>
			<form method="post" name="paymentForm" id="paymentForm" action="<?php echo $payment_options['productionURL']; ?>">
			<?php else : ?>
			<form method="post" name="paymentForm" id="paymentForm" action="<?php echo $payment_options['developmentURL']; ?>">
			<?php endif; ?>
				<input type="hidden" name="Version" value="<?php echo $payment_options['Version']; ?>" />
				<input type="hidden" name="MerID" value="<?php echo $payment_options['MerID']; ?>" />
				<input type="hidden" name="AcqID" value="<?php echo $payment_options['AcqID']; ?>" />
				<input type="hidden" name="MerRespURL" value="<?php echo $payment_options['MerRespURL']; ?>" />
				<input type="hidden" name="SignatureMethod" value="<?php echo $payment_options['SignatureMethod']; ?>" />
				<input type="hidden" name="CaptureFlag" value="<?php echo $payment_options['CaptureFlag']; ?>" />
				<input type="hidden" name="PurchaseCurrency" value="<?php echo $payment_options['PurchaseCurrency']; ?>" />
				<input type="hidden" name="PurchaseCurrencyExponent" value="<?php echo $payment_options['PurchaseCurrencyExponent']; ?>" />
				<input type="hidden" name="PurchaseAmt" value="<?php echo $PurchaseAmt; ?>" />
				<input type="hidden" name="Signature" value="<?php echo $Signature; ?>" />
				<input type="hidden" name="OrderID" value="<?php echo $order_details['order_id']; ?>" />
			</form>
		</div>
		<script language="JavaScript">
			document.forms["paymentForm"].submit();
		</script>
	</body>
</html>

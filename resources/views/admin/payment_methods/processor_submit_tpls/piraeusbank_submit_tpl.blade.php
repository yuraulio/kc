<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Submitting data to PiraeusBank Servers</title>
	</head>
	<body>
		<div style="display: none;">
			<form method="post" name="paymentForm" id="paymentForm" action="<?php echo $payment_options['PostUrl']; ?>">
				<input type="hidden" name="AcquirerId" value="<?php echo $sbt_data['AcquirerId']; ?>"/>
				<input type="hidden" name="MerchantId" value="<?php echo $sbt_data['MerchantId']; ?>"/>
				<input type="hidden" name="PosId" value="<?php echo $sbt_data['PosId']; ?>"/>
				<input type="hidden" name="User" value="<?php echo $sbt_data['User']; ?>"/>
				<input type="hidden" name="LanguageCode" value="<?php echo $sbt_data['LanguageCode']; ?>"/>
				<input type="hidden" name="MerchantReference" value="<?php echo $sbt_data['MerchantReference']; ?>"/>
				<input type="hidden" name="ParamBackLink" value="<?php echo $sbt_data['ParamBackLink']; ?>"/>
			</form>
		</div>
		<script>
			document.forms["paymentForm"].submit();
		</script>
	</body>
</html>

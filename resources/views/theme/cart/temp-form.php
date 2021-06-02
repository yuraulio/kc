



						<?php
        $amount_2_send = rand(1,5).".".rand(0,99);
        $prod_array = array("Prod 1","Prod 2","Prod 3","Prod 4","Prod 5","Prod 6","Prod 7","Prod 8","Prod 9","Prod 10","Prod 11","Prod 12","Prod 13","Prod 14","Prod 15","Prod 16","Prod 17","Prod 18","Prod 19");
		$prod_id = rand(0,count($prod_array)-1);
		$prod_2_display = $prod_array[$prod_id];
		$order_id = rand(0,99).date("YmdHms")

	?>

		<!-- <form name="demo" id="demo" method="POST" action="./checkout_route.php?act=send" accept-charset="UTF-8" > -->
		<table>
		<tr>
		<td>
			<input type="button" name="checkout" value="checkout" onclick="javascript:acceptance()" />
		</td>
		<td>
			I Agree &nbsp; <input type="checkbox" id="accbtn">
		</td>
		</tr>

		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>

		<!-- <tr>
		<td>Γλώσσα</td><td>
		<select name="lang">
		<option value="" selected="selected">None</option>
		<option value="el">Greek</option>
		<option value="en">English</option>
		<option value="fr">French</option>
		</select>
		</tr> -->
		<tr>
		<tr>
		<td>Merchant id</td><td>
			<input type="text" name="mid" size="10" value=""/>
		</td>
		</tr>
		<tr>
		
		<td>Order id</td><td><input type="text" name="orderid" size="60" value="<?php echo $order_id ?>"/></td>
		
		</tr>
		<tr>
		<td>Order description</td><td><input type="text" size="50" maxlength="128" name="orderDesc" value="{{ $item->name }}"/></td>
		</tr>
		
		<tr>
		<td>Ποσό</td><td><input type="text" name="orderAmount" value="{{ Cart::instance('default')->total() }}"/></td>
		</tr>
		<tr>
		<td>Νόμισμα</td><td>
		<select name="currency">
		<option value="EUR" selected="selected">EUR</option>
		<option value="USD">USD</option>
		<option value="GBP">GBP</option>
		</select>
		</td>
		</tr>
		<tr>
		<td>Email Πελάτη</td><td><input type="text" name="payerEmail" size="35" maxlength="64" value="@if($user = Sentinel::check()) {{ $user->email }} @endif"/></td>
		</tr>
		<!-- <tr>
		<td>Payer phone</td><td><input type="text" name="payerPhone" size="25" maxlength="30" value=""/></td>
		</tr> -->
		<tr>
		<td>Τρόπος Πληρωμής</td><td>
		<select name="payMethod">
		<option value="">No pre selection</option>
		<option value="visa">Visa</option>
		<option value="visaElectron">Visa Electron</option>
		<option value="mastercard">Mastercard</option>
		<option value="maestro">Maestro</option>
		</select>
		</tr>
		<tr>
		<td>Τύπος Συναλλαγής</td><td>
		<select name="trType">
		<option value="">Default</option>
		<option value="1">Payment</option>
		<option value="2">Pre authorization</option>
		</select>
		</tr>

		<tr>
		<td>Bill to country</td><td>
		<select name="billCountry">
		<option value="" selected="selected"></option>
		<option value="EL">Greece</option>
		<option value="UK">United Kingdom</option>
		<option value="US">USA</option>
		</select>
		</tr>
		<tr>
		<td>Bill to state</td><td><input type="text" name="billState" value=""/></td>
		</tr>
		
		<tr>
		<td>Bill ship to zip</td><td><input type="text" name="billZip" value=""/></td>
		
		</tr>
		<tr>
		<td>Bill to city</td><td><input type="text" name="billCity" value=""/></td>
		</tr>
		<tr>
		<td>Bill to address</td><td><input type="text" name="billAddress" value=""/></td>
		</tr>
		

		
		<!-- <tr>
		<td>addFraudScore</td><td><input type="text" name="addFraudScore" size="5"
		value=""/></td>
		</tr>
		
		<tr>
		<td>maxPayRetries</td><td><input type="text" name="maxPayRetries" size="5"
		value=""/></td>
		
		</tr>
		<tr>
		<td>reject3dsU</td><td><input type="text" name="reject3dsU" size="2" maxlength="1"
		value=""/> (Y/N)</td>
		</tr>
		<tr>
		<td>Block score</td><td><input type="text" name="blockScore" size="5" value=""/>
		</td> -->
		</tr>
		<!-- <tr>
		<td>CSS url</td><td><input type="text" name="cssUrl" size="70"
		value=""/></td>
		</tr> -->

		<tr>
		<td>Σελίδα confirmation</td><td><input type="text" name="confirmUrl" size="70" value="/booking_success"/></td>
		</tr>
		<tr>
		<td>Σελίδα Αποτυχίας</td><td><input type="text" size="70" name="cancelUrl" value="/booking_failed"/></td>
		</tr>

		</table>
		<!-- </form> -->
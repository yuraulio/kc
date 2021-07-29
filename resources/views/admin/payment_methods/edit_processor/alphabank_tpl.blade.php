<p>
	<h4>No need to inform Alphabank for  URLs, they are passed as arguments</h4>
	<strong>SSL is required on this URL</strong> <span><?php echo URL::to('payment-dispatch'); ?></span>
</p>

<p>
	<strong>Order Description</strong> <span>This is a required field...</span><br />
	<strong>Amount</strong> <span>Example (Euro): 1,50 or 1.50 or 345.20 (a decimal with 2 digits after the decimal point)</span><br />
	<strong>Additional Var</strong> <span>We have access to additional vars named: var1 to var5</span>
</p>

<br />
<div class="pl-lg-4">
<div class="control-group">
	<label class="control-label" for="mid">Merchant ID</label>
	<div class="controls">
	  	<input type="text" name="processor[mid]" class="form-control" id="mid" value="{{isset($method['processor_options']['mid']) ? $method['processor_options']['mid'] : ''}}" placeholder="" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="mid">Shared Secret Key</label>
	<div class="controls">
	  	<input type="text" name="processor[shared_secret_key]" class="form-control" id="shared_secret_key" value="{{isset($method['processor_options']['shared_secret_key']) ? $method['processor_options']['shared_secret_key'] : ''}}" placeholder="" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="currency">Currency</label>
	<div class="controls">
	  	<select name="processor[currency]" id="currency" class="form-control"> 
	  		<option value="EUR">Euro</option>
	  	</select>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="lang">Lang</label>
	<div class="controls">
	  	<select name="processor[lang]" id="lang" class="form-control">
	  		<option value="el" {{isset($method['processor_options']['lang']) && $method['processor_options']['lang'] == 'el' ? 'selected' : ''}}>Greek</option>
	  		<option value="en"{{isset($method['processor_options']['lang']) && $method['processor_options']['lang'] == 'en' ? 'selected' : ''}}>English</option>
	  	</select>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="confirmUrl">Confirmation URL</label>
	<div class="controls">
	  	<input type="text" name="processor[confirmUrl]" class="form-control" id="confirmUrl" value="{{$method['processor_options']['confirmUrl']}}" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="cancelUrl">Cancellation URL</label>
	<div class="controls">
	  	<input type="text" name="processor[cancelUrl]" class="form-control" id="cancelUrl" value="{{$method['processor_options']['cancelUrl']}}" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="developmentURL">Development URL</label>
	<div class="controls">
	  	<input type="text" name="processor[developmentURL]" class="form-control" id="developmentURL" value="{{$method['processor_options']['developmentURL']}}" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="productionURL">Production URL</label>
	<div class="controls">
	  	<input type="text" name="processor[productionURL]" class="form-control" id="productionURL" value="{{$method['processor_options']['productionURL']}}" />
	</div>
</div>
</div>

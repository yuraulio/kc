
<div class="pl-lg-4">
<div class="control-group">
	<label class="control-label" for="mid">Stripe Key</label>
	<div class="controls">
	  	<input type="text" name="processor[key]" class="form-control" id="mid" value="{{isset($method['processor_options']['key']) ? $method['processor_options']['key'] : ''}}" placeholder="" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="mid">Stripe Secret Key</label>
	<div class="controls">
	  	<input type="text" name="processor[secret_key]" class="form-control" id="mid" value="{{isset($method['processor_options']['secret_key']) ? $method['processor_options']['secret_key'] : ''}}" placeholder="" />
	</div>
</div>


</div>

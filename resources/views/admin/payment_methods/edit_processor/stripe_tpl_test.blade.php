
<div class="pl-lg-4">
<div class="control-group">
	<label class="control-label" for="mid">Stripe Test Key</label>
	<div class="controls">
	  	<input type="text" name="test_processor[key]" class="form-control" id="mid" value="{{isset($method['test_processor_options']['key']) ? $method['test_processor_options']['key'] : ''}}" placeholder="" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="mid">Stripe Test Secret Key</label>
	<div class="controls">
	  	<input type="text" name="test_processor[secret_key]" class="form-control" id="mid" value="{{isset($method['test_processor_options']['secret_key']) ? $method['test_processor_options']['secret_key'] : ''}}" placeholder="" />
	</div>
</div>


</div>

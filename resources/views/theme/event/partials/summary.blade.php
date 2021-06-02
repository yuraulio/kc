<!-- SUMMARY -->
<div id="section-summary" >
    <div class="container">
    	<div class="row">
    		<div class="col-lg-12">
    			<h2>Summary</h2>
    		</div>
    	</div>
    </div>

	<div class="container" >
		<div class="summary-wrap">
	   		<div class="is-flex">
	   			<!-- col-lg-3 col-md-3 col-sm-3 col-xs-4 -->
	   			<?php if (isset($content['c_fields']['simple_text'][16]) && $content['c_fields']['simple_text'][16]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/greek.svg" alt="Greek" title="Greek" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][16]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][17])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][17]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

			    <?php if (isset($content['c_fields']['simple_text'][0]) && $content['c_fields']['simple_text'][0]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/date.svg" alt="Date" title="Date" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][0]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][1])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][1]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

			    <?php if (isset($content['c_fields']['simple_text'][2]) && $content['c_fields']['simple_text'][2]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/hours.svg" alt="Hours" title="Hours" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][2]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][3])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][3]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

			    <?php if (isset($content['c_fields']['simple_text'][22]) && $content['c_fields']['simple_text'][22]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/days.svg" alt="Days" title="Days" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][22]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][23])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][23]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

	   			<?php if (isset($content['c_fields']['simple_text'][4]) && $content['c_fields']['simple_text'][4]['value'] != '') : ?>
		    	<div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/EQFLevel5.svg" alt="EFQ Level 5" title="EFQ Level 5" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][4]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][5]) && $content['c_fields']['simple_text'][5] != '') : ?>
			    <p>{{ $content['c_fields']['simple_text'][5]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

			    <?php if (isset($content['c_fields']['simple_text'][6]) && $content['c_fields']['simple_text'][6]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/KnowCrunchDeree.svg" alt="KnowCrunch Deree" title="KnowCrunch Deree" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][6]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][7])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][7]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

			    <?php if (isset($content['c_fields']['simple_text'][8]) && $content['c_fields']['simple_text'][8]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/user_level.svg" alt="User Level" title="User Level" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][8]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][9])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][9]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

			    <?php if (isset($content['c_fields']['simple_text'][10]) && $content['c_fields']['simple_text'][10]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/class.svg" alt="Class" title="Class" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][10]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][11])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][11]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>
			<!-- </div>
			<div class="row"> -->
			    <?php if (isset($content['c_fields']['simple_text'][20]) && $content['c_fields']['simple_text'][20]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/time.svg" alt="Time" title="Time" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][20]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][21])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][21]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>
			<!-- </div>
			<div class="row"> -->
				<?php if (isset($content['c_fields']['simple_text'][12]) && $content['c_fields']['simple_text'][12]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/download.svg" alt="Download" title="Download" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][12]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][13]) && $content['c_fields']['simple_text'][13] != '') : ?>
			    <p>{{ $content['c_fields']['simple_text'][13]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

			    <?php if (isset($content['c_fields']['simple_text'][14]) && $content['c_fields']['simple_text'][14]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/laptop.svg" alt="Laptop" title="Laptop" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][14]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][15])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][15]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

			    <?php if (isset($content['c_fields']['simple_text'][12]) && $content['c_fields']['simple_text'][12]['value'] != '') : ?>
			    <div class="summary-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/summary_icons/payment.svg" alt="Payment" title="Payment" /></div>
			    <h5 class="sum-icon-title">{{ $content['c_fields']['simple_text'][18]['value'] }}</h5>
			    <?php if (isset($content['c_fields']['simple_text'][19])) : ?>
			    <p>{{ $content['c_fields']['simple_text'][19]['value'] }}</p>
			    <?php endif; ?>
			    </div>
			    <?php endif; ?>

		    </div>
	    </div>
	</div>

</div>
<!-- SUMMARY END -->

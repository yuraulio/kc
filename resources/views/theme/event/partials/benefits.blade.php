<!-- SUMMARY -->
<div id="section-benefits" class="tab-pane">
    <div class="container">
    	<div class="row">
    		<div class="col-lg-12">
    			<h2>Benefits of attending</h2>
    		</div>
    	</div>
    </div>

	<div class="container">
		<div class="row">
	   	<!--	<div  id="benefits">-->
				   <!-- col-lg-3 col-md-3 col-sm-3 col-xs-4 -->
				   
				   <?php $category = 'freepresentations';  $benefit = $content->benefit()->where('category',$category)->first();
				   if (isset($benefit) && $benefit->title != '') : ?>
			    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				<div class="benefit-tiles">
				<div class="sum-icon"><img class="summary_ic" src="theme/assets/img/new_icons/benefits/cloud_download.svg" alt="FREE PRESENTATIONS" title="FREE PRESENTATIONS" />
				<h3>{{ $benefit->title }}</h3>
				{!! $benefit->description !!}
				</div>
				</div>
			    </div>
			    <?php endif; ?>

				<?php $category = 'e-learning';  $benefit = $content->benefit()->where('category',$category)->first();
				   if (isset($benefit) && $benefit->title != '') : ?>	
				   			    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
	
				<div class="benefit-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/new_icons/benefits/ondemand_video.svg" alt="FREE E-LEARNING" title="FREE E-LEARNING" />
				<h3>{{ $benefit->title }}</h3>
				{!! $benefit->description !!}
				</div>
		</div>
			    </div>
			    <?php endif; ?>
				<?php $category = 'support group';  $benefit = $content->benefit()->where('category',$category)->first();
				   if (isset($benefit) && $benefit->title != '') : ?>			  
						    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

				  <div class="benefit-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/new_icons/benefits/history.svg" alt="SUPPORT GROUP" title="SUPPORT GROUP" />
				<h3>{{ $benefit->title }}</h3>
				{!! $benefit->description !!}
				</div>
		</div>
			    </div>
			    <?php endif; ?>
				<?php $category = 'jobs access';  $benefit = $content->benefit()->where('category',$category)->first();
				   if (isset($benefit) && $benefit->title != '') : ?>		
				   			    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

				   	 <div class="benefit-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/new_icons/benefits/book.svg" alt="JOBS ACCESS" title="JOBS ACCESS" /><h3>
				<h3>{{ $benefit->title }}</h3>
				{!! $benefit->description !!}
				</div>
		</div>
			    </div>
			    <?php endif; ?>

				<?php $category = 'projects info';  $benefit = $content->benefit()->where('category',$category)->first();
				   if (isset($benefit) && $benefit->title != '') : ?>	
				   			    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

				   	<div class="benefit-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/new_icons/benefits/assessment.svg" alt="PROJECTS INFO" title="PROJECTS INFO" />
				<h3>{{ $benefit->title }}</h3>
				{!! $benefit->description !!}
				</div>
		</div>
			    </div>
			    <?php endif; ?>

				<?php $category = 'events access';  $benefit = $content->benefit()->where('category',$category)->first();
				   if (isset($benefit) && $benefit->title != '') : ?>		
				   			    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

				   	    <div class="benefit-tiles">
			        <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/new_icons/benefits/mood.svg" alt="EVENTS ACCESS" title="EVENTS ACCESS" />
					<h3>{{ $benefit->title }}</h3>
				{!! $benefit->description !!}
				</div>
		</div>
			    </div>
			    <?php endif; ?>

				<?php $category = 'free recaps';  $benefit = $content->benefit()->where('category',$category)->first();
				   if (isset($benefit) && $benefit->title != '') : ?>				
						    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

				<div class="benefit-tiles">
			    <div class="sum-icon"><img class="summary_ic" src="theme/assets/img/new_icons/benefits/event_available.svg" alt="FREE RECAPS" title="FREE RECAPS" />
				<h3>{{ $benefit->title }}</h3>
				{!! $benefit->description !!}
				</div>
		</div>
			    </div>
			    <?php endif; ?>

				
			   

		    </div>
	    <!--</div>-->
	</div>

</div>
<!-- SUMMARY END -->

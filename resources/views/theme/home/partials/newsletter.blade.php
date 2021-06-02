<div class="newsletter-section">
    <div class="container">
        <div class="row">
        	 <div class="col-lg-12 col-xs-12">
        		<h2 class="newsletter-title">Join our newsletter today</h2>
        	</div>
		</div>
        <div class="newsletterReponse"></div>
		<form method="POST" action="" name="newsletter_form" class="form-horizontal newsletter_form">
        {{ csrf_field() }}
		<div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            	<div class="form-group startrow">
            		<input type="text" class="form-control" id="name" name="name" placeholder="Name">
            	</div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            	<div class="form-group startrow">
            		<input type="text" class="form-control" id="surname" name="surname" placeholder="Surname">
            	</div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            	<div class="form-group startrow">
            		<input type="text" class="form-control" id="email" name="email" placeholder="Email">
            	</div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	            <div class="form-group endrow">
	                <input type="hidden" id="current_url_input" name="current_url" value="{{ Request::url() }}" />
	                <input type="hidden" name="lang" value="en">
                    <input type="hidden" name="please_fill" value="">
	                <!-- <label for="email" class="control-label"></label> -->
		            <button class="btn btn-newsletter" id="applyToNewsletter">SUBSCRIBE
		            </button>
		            <!-- <i class="fa fa-envelope-o" aria-hidden="true"></i> -->
	            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xs-12 text-center">
            	<span id="loadingDiv"><img class="img-responsive center-block" src="theme/assets/img/ajax-loader-blue.gif" /></span>

        	</div>
        </div>
        </form>
    </div>
</div>

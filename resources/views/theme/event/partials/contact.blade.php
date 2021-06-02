@if(isset($section_contact))
<div id="section-contact" class="event-section">
	<div class="container">
    	<div class="row">
    		<div class="col-lg-6 col-md-6 col-sm-6">
                <div class="contact-wrap2">
                		<h2>{{ $section_contact->title }}</h2>
	        			{!! $section_contact->body !!}
	        			<p>
	        			@if($section_contact->short_title != null)	<span class="contact-phone"><img src="theme/assets/img/phone.svg" alt="Telephone" title="Telephone" />{{ $section_contact->short_title }}</span><br />@endif
	        				<span class="telltip small">{{ $section_contact->header }}</span><br /><br />
	        			</p>
        			<!-- <h2>Contact Us</h2>
        			<p>We are here to help you.<br />
        				Feel free to contact us for any informatin or questions or subscribe to our newsletter and stay updated.
        			</p>
        			<p>
        				 <span class="contact-phone"><img src="theme/assets/img/phone.svg" alt="Telephone" />+30 2310-895800</span><br />
        				<span class="telltip small">After language selection dial 1332 for Ms None</span><br /><br />

        			</p> -->
                </div>
    		</div>
    		<div class="col-lg-6 col-md-6 col-sm-6">
                <div id="value" class="contactUsReponse"></div>
			    <form id="doall" method="POST" action="" class="contactUsForm">
			        <div id="contact_page_form_container">
			            <div class="row">
			                <div class="col-xs-12 col-sm-12">
			                    <div class="row">
			                        <div class="form-group">
			                            <label for="cname" class="sr-only">name*</label>
			                            <input type="text" class="form-control" id="cname" name="cname" placeholder="Name*" value="<?php if (isset($form['cname'])) : echo $form['cname']; endif; ?>">
			                        </div>
			                    </div>
			                    <div class="row">
			                        <div class="form-group phone_input">
			                            <label for="csurname" class="sr-only">surname*</label>
			                            <input type="text" class="form-control" id="csurname" name="csurname" placeholder="Surname*" value="<?php if (isset($form['csurname'])) { echo $form['csurname']; } ?>">
			                        </div>
			                    </div>
			                    <div class="row">
			                        <div class="form-group">
			                            <label for="cemail" class="sr-only">email*</label>
			                            <input type="text" class="form-control" id="cemail" name="cemail" placeholder="Email*" value="<?php if (isset($form['cemail'])) { echo $form['cemail']; } ?>">
			                        </div>
			                    </div>

			                </div>
			                <div class="col-xs-12 col-sm-12 textarea_container">
			                	<div class="row">
			                        <div class="form-group">
					                    <label for="cmessage" class="sr-only">Comment:</label>
					                    <textarea name="cmessage" rows="7" class="form-control" id="comment" placeholder="Message*"><?php if (isset($form['cmessage'])) { echo $form['cmessage']; } ?></textarea>
			                    	</div>
			                    </div>
			                </div>
			            </div>

							<div class="mobileR" style="display: block;">
                                {{-- <div class="custom-checkbox" id="terms" style="height: 34px;">
                                  <input class="c-box" id="accept" type="checkbox" value="0" name="accept" required="" >
                                  <label> I have read, agree &amp; accept the terms &amp; conditions and data privacy policy</label>

                                </div> --}}
                                <p class="small">We respect your personal data. By contacting us, you accept that we will contact you to inform you about events we believe might be of interest to you or tips & news. Please read our <a class="termlink" target="_blank" title="View the data privacy policy" href="/data-privacy-policy">data privacy policy</a>.</p>
                                <div class="custom-checkbox-no" id="terms" style="height: 34px;">
                                          <input class="c-box" id="accept" type="checkbox" value="0" name="accept" required="">
                                          <label>I have read, I agreeee &amp; I accept</label>


                                        </div>
                                <br />
                                <input type="hidden" name="eventtitle" value="{{ $content->title }} - {{ $content->subtitle }}" />
                                {{-- <div class="mobileR">
                                View the <a id="toggleterms" target="_blank" title="View the Terms and conditions" href="/terms-privacy">terms &amp; conditions</a> and <a class="termlink" target="_blank" title="View the data privacy policy" href="/data-privacy-policy">data privacy policy</a></div>

                                <br /> --}}

	                        	{{-- <div class="custom-checkbox-uni" id="terms" style="margin-bottom: 14px;">

                                <input type="hidden" name="eventtitle" value="{{ $content->title }}" />
								<input class="c-box" id="accept" type="checkbox" value="0" name="accept" required="" checked="checked"><a id="toggleterms" target="_blank" title="View Terms and conditions" href="/terms-privacy">I agree &amp; accept the terms &amp; conditions</a>
								</div> --}}
                            <a title="SEND MESSAGE" id="sendme" class="btn btn-green-invert contactUsSubmit">SEND MESSAGE</a>
                            <div id="loadingDivN"><img class="img-responsive" src="theme/assets/img/ajax-loader-blue.gif" alt="Loader" title="Loader" /></div>
                        </div>
			        </div>
			    </form>
			</div>
		</div>
    </div>
</div>
@endif

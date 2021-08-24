@extends('theme.layouts.master')
@section('metas')

    <title>{{ $page['name'] }}</title>
   {!! $page->metable->getMetas() !!}

@endsection
@section('content')
@include('theme.preview.preview_warning', ["id" => $page->id, "type" => "page", "status" => $page->status])
<main id="main-area" class="with-hero" role="main">
<script type="application/ld+json">
{
	"@context": "http://schema.org/",
	"@type": "ContactPage",
	"name": "{{ $page['name'] }}",
	"description":"{!! $page['content'] !!}",
	"breadcrumb": "Home > Contact us"
}
</script>
				@if (!empty($page['medias']))
				<section class="section-hero" style="background-image:url(<?= asset(get_image($page['medias'], 'header-image')); ?>)">

					<div class="overlay"></div>
					<div class="container">
						<div class="hero-message pad-r-col-6">
							<h1>{{ $page['name'] }}</h1>
							<h2>Have a question?</h2>
						</div>
					</div>
					</section>
				@else
						<section class="section-hero section-hero-small section-hero-blue-bg">
			<div class="container">
				<div class="hero-message">
					<h1>{{ $page->title }}</h1>
				</div>
			</div>
			<!-- /.section-hero -->
		</section>

				@endif
				<!-- /.section-hero -->

				<section class="form-section">
					<div class="container">
						<div class="row">
							<div class="col6 col-sm-12">
								<div class="text-area">
										{!! $page['content'] !!}

								</div>
							</div>
							<div class="col6 col-sm-12">
								<div class="form-area-wrapper">
									<div class="form-wrapper blue-form w-m-bottom">
										<form id="doall" method="POST" class="contactUsForm" novalidate>
											<h3 class="form-h3">We would love to hear from you</h3>
											<label>First name <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text"  id="cname" name="cname"/>
											</div>
											<label>Last name <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="csurname" name="csurname">
											</div>
											<label>Email <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="email" id="cemail" name="cemail">
											</div>
											<label>Mobile phone <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="ctel" name="ctel">
											</div>
											<label>Your message <span>*</span></label>
											<div class="input-safe-wrapper">
												<textarea  class="required" id="comment" name="cmessage"></textarea>
											</div>
											<div class="checkbox-row custom-checkbox-wrapper">
	                                            <div class="custom-checkbox">
	                                                <input type="checkbox" id="accept" name="receive-messages" value="accept">
	                                                <span></span>
	                                            </div>
	                                            <label for="receive-messages">I have read, agree upon & accept the <a href="/terms" target="_blank">terms & <br/>conditions</a> and <a href="/data-privacy-policy" target="_blank">data privacy policy</a>. </label>
	                                        </div>
	                                        <div class="submit-area-custom">
	                                        	<button type="button" id="sendme" class="btn btn--md btn--secondary contactUsSubmit">Apply Now</button>
	                                        </div>
                                        </form>
										<div class="alert-outer" hidden>
											<div class="container">
												<div class="alert-wrapper success-alert">
													<div class="alert-inner">
														<p id="contact-success"></p>
														<a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
													</div>
												</div>
											</div>
										<!-- /.alert-outer -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<!-- /.form-section -->
				</section>
			</main>

@endsection
@section('scripts')


<script>
    document.getElementById('header').classList.add('header-transparent');
</script>


<script type="text/javascript">


$(document).ready(function() {



  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      }
    });
  };
  $("#ctel").inputFilter(function(value) {
  return /^\d*$/.test(value);
});

});


/* Contact Us Methods */
    $(".contactUsSubmit").on("click", function () {

        var thec = $('input#accept');
        if (thec.prop("checked") === false) {

          alert('Please accept the terms and condition and our data privacy policy');

        }
        else {

            var contactUrl = '/contact-us';
            //dataLayer.push({'event' : 'leadSubmitted', 'formName' : 'lead'});

            $.ajax({ url: contactUrl, type: "post",
                data: $(".contactUsForm").serialize(),
                success: function(data) {

                    //alert('HO');
                    $('.contactUsForm').find("input[type=text], input[type=email], textarea").removeClass('verror');
                    if (Number(data.status) === 0) {
                        //var html = '<ul>';
                        $.each(data.errors, function (key, row) {
                            //console.log(data.errors);

                            $('.contactUsForm').find('input[name="'+key+'"], textarea[name="'+key+'"]').addClass('verror');
                            $('.contactUsForm').find('input[name="'+key+'"]').attr('placeholder', row);
                            $('.contactUsForm').find('textarea[name="'+key+'"]').attr('placeholder', row);

                        });

                    } else {
						var p = document.getElementById('contact-success').textContent = data['message'];

						var img = document.createElement('img');
						img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
						img.setAttribute('alt',"Info Alert" )

						$('#contact-success').append(img);
                        $('.alert-outer').show()
                        $('.contactUsForm').find('input[type=text], input[type=email], textarea').val('');
                        $(".contactUsForm").slideUp();
                        $(".contact-wrap").slideUp();
                        fbq('track', 'Lead');


                    }
                }
            });


        }


    });


</script>
@stop

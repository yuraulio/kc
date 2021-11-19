@extends('theme.layouts.master')

@section('metas')

    <title>{{ $page['name'] }}</title>
   {!! $page->metable->getMetas() !!}
@endsection

@section('content')
@include('theme.preview.preview_warning', ["id" => $page['id'], "type" => "content", "status" => $page['status']])
<script type="text/javascript">
    var errorMessages = {
        requiredError: "Please fill in all mandatory data.",
        emailError: "Please enter a valid email address.",
    };
</script>
<main id="main-area" class="with-hero" role="main">
@if (!empty($page['medias']))
				<section class="section-hero" style="background-image:url(<?= asset(get_image($page['medias'], 'header-image')); ?>)">

					<div class="overlay"></div>
					<div class="container">
						<div class="hero-message pad-r-col-6">
							<h1>{{ $page['name'] }}</h1>
							<h2>{{ $page['title'] }}</h2>
						</div>
					</div>
					</section>
				@else
						<section class="section-hero section-hero-small section-hero-blue-bg">
			<div class="container">
				<div class="hero-message">
                    <h1>{{ $page['name'] }}</h1>
                    <h2>{{ $page['title'] }}</h2>
				</div>
            </div>
</section>
            @endif
				<section class="form-section">
					<div class="container">
						<div class="row">
							<div class="col6 col-sm-12">
								<div class="text-area">
                                {!! $page['summary'] !!}
								</div>
							</div>
							<div class="col6 col-sm-12">
								<div class="form-area-wrapper">
									<div class="form-wrapper blue-form w-m-bottom">
										<form id="beForm" method="POST" action="" class="beForm" novalidate>
											<h3 class="form-h3">Apply to become a KnowCrunch’s instructor</h3>
											<label>First name <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="iform-name" name="iform-name"/>
											</div>
											<label>Last name <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text"id="iform-surname" name="iform-surname">
											</div>
											<label>Email <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="email"  id="iform-email" name="iform-email">
											</div>
											<label>Mobile phone <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="iform-phone" name="iform-phone">
											</div>
											<label>Linkedin profile link <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="iform-linkedin" name="iform-linkedin">
											</div>
											<label>Fluent in languages <span>*</span><br/><small>(separated by a comma)</small> </label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="iform-lang" name="iform-languages">
											</div>
											<label>Experience in training (years) <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="iform-duration" name='iform-duration'>
											</div>
											<label>Training topics expertise <span>*</span><br/><small>(separated by a comma)</small> </label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="iform-expertise" name='iform-expertise'>
											</div>

											<div class="checkbox-row custom-checkbox-wrapper">
	                                            <div class="custom-checkbox">
	                                                <input type="checkbox" id="accept" name="receive-messages" value="accept">
	                                                <span></span>
	                                            </div>
												<label class="insructor-form" for="receive-messages">I accept the  <a href="/terms" target="_blank"> Terms & Conditions </a> and I confirm that I have read the <a href="/data-privacy-policy" target="_blank">Data Privacy Policy.</a>. </label>
	                                        </div>
	                                        <div class="submit-area-custom">
	                                        	<button type="button" class="btn btn--md btn--secondary beSubmit">Apply Now</button>
	                                        </div>
										</form>
									</div>
									<div class="alert-outer" hidden>
											<div class="container">
												<div class="alert-wrapper success-alert">
													<div class="alert-inner">
														<p id="beIns-success"></p>
														<a href="javascript:void(0)" class="close-alert"><img src="/theme/assets/images/icons/alert-icons/icon-close-alert.svg" alt="Close Alert"/></a>
													</div>
												</div>
											</div>
										<!-- /.alert-outer -->
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



$(document).on('click', '.beSubmit', function(e) {
    e.preventDefault();

    var thec = $('input#accept');
    if (thec.prop("checked") === false) {

        alert('Please accept the terms and condition');

    }
    else {


        var checkoutUrl = 'applyforbe';

        var fdata = $("#beForm").serialize();

        $.ajax({ url: checkoutUrl, type: "post",
            data: fdata,
            success: function(data) {
                //alert('HO');
               // console.log(data.errors);
               // $('#beForm').find("input[type=text]").removeClass('verror');
                if (Number(data.status) === 0) {
                    //var html = '<ul>';
                 /*   $.each(data.errors, function (key, row) {
                        //console.log(data.errors);
                        var newkey = key.replace('.', '');
                        //console.log(newkey);

                        $('#beForm').find('input#'+newkey).addClass('verror');

                        if(newkey.startsWith("student")) {

                            var s = $('#beForm').find('input#'+newkey).attr('placeholder');

                            var pl = 'The '+s+' is required';

                            $('#beForm').find('input#'+newkey).attr('placeholder', pl);

                        }
                        else {

                            $('#beForm').find('input#'+newkey).attr('placeholder', row);
                        //$('.cartForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                        }

                        $('.errorReponse').html(data.message);
                    });*/

                } else {

                    var p = document.getElementById('beIns-success').textContent = data['message'];
						var img = document.createElement('img');
						img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
						img.setAttribute('alt',"Info Alert" )

						$('#beIns-success').append(img);
					//	console.log(p);
                        $('.alert-outer').show()
                    $('#beForm').find('input[type=text], input[type=email], textarea').val('');
                    $('#beForm').slideUp();
                    $('.successHide').hide();


                }
            }
        });

    }
    });

</script>


@stop



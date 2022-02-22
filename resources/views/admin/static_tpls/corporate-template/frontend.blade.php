@extends('theme.layouts.master')
@section('metas')
   {!! $page->metable->getMetas() !!}

@endsection
@section('content')
@include('theme.preview.preview_warning', ["id" => $page['id'], "type" => "page", "status" => $page['status']])
<main id="main-area" class="with-hero" role="main">
@if (!empty($page['medias']))
    <section class="section-hero" style="background-image:url({{cdn(get_image($page['medias'], 'header-image'))}})">

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
    </div>
</div>
</section>
            @endif
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
									<div class="form-wrapper blue-form">
										<form id="doall" novalidate>
											<h3 class="form-h3">Request for corporate training quotation</h3>
											<label>First name <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="cfirstname" name="cfirstname"/>
											</div>
											<label>Last name <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text"  id="csurname" name="csurname">
											</div>
											<label>Position title <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="cjob" name="cjob">
											</div>
											<label>Company name <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="text" id="ccompany" name="ccompany">
											</div>
											<label>Email <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required" type="email" id="cemail" name="cemail">
											</div>
											<label>Mobile phone <span>*</span></label>
											<div class="input-safe-wrapper">
												<input class="required"type="text" id="ctel" name="ctel" >
											</div>
											<div class="checkbox-row custom-checkbox-wrapper">
	                                            <div class="custom-checkbox">
	                                                <input type="checkbox" id="accept" name="accept" value="accept">
	                                                <span></span>
	                                            </div>
	                                            <label for="receive-messages">I have read, agree upon & accept the <a href="/terms" target="_blank">terms & <br/>conditions</a> and <a href="/data-privacy-policy" target="_blank">data privacy policy</a>. </label>
	                                        </div>
	                                        <div class="submit-area-custom">
	                                        	<button id="sendme1" type="button" class="btn btn--md btn--secondary contactUsSubmit1">Contact me</button>
	                                        </div>
										</form>
										<div class="alert-outer" hidden>
										<div class="container">
											<div class="alert-wrapper success-alert">
												<div class="alert-inner">
													<p id="coorporate-success"></p>
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

				<section class="section-icon-text">
					<div class="container">
						<div class="row row-flex row-flex-50">

                              @foreach($benefits as $benefit)

                              <div class="col-4 col-sm-6 col-xs-12">
									<div class="icon-text-box">
										<div class="icon-wrapper">
											<img src="{{ cdn('/theme/assets/images/icons/knowcrunch-corporate-training-icon-01.svg') }}" alt=""/>
										</div>
										<h3>{{$benefit['name']}}</h3>
										{!!$benefit['description']!!}
									</div>
								<!-- /.col-4.col-sm-6 col-xs-12 -->
								</div>
                              @endforeach

						</div>
					</div>
				</section>
{{--
				<section class="section-big-testimonials">
					<h2 class="section-title">What executives say about us</h2>
					<div class="user-testimonial-wrapper">
						<div class="container"> <!-- Feedback 18-11 changed -->
							<div class="user-testimonial-big-single owl-carousel">
								<div class="slide">
									<div class="testimonial-box">
										<div class="author-infos">
											<span class="author-name">
											Anastasia Tsimxmcnvxv</span>
											<span class="author-job">Local Marketing ExeCUTIVE</span>
										</div>
										<div class="testimonial-text">
											<p>“Η συμμετοχή μου σε αυτό το σεμινάριο, υπήρξε κάτι παραπάνω από ικανοποιητική. Γνώρισα πολύ ενδιαφέροντες ανθρώπους, και καθηγητές που πραγματικά πολλές φορές υπερέβαλλαν εαυτό για να μας βοηθήσουν. Το αντικείμενο το προσέγγισα στην αρχή με δισταγμό, αλλά σχεδόν αμέσως κατάλαβα ότι είναι σίγουρα το marketing όχι μόνο του μέλλοντος, αλλά κυρίως του παρόντος. Ο όγκος των πληροφοριών που πήρα από αυτό το σεμινάριο είναι πραγματικά πολύ μεγάλος, αλλά και πάρα πολύ σημαντικός. Στο τέλος του σεμιναρίου, και καθώς προετοιμαζόμουν τόσο για τις ομαδικές εργασίες, όσο και για τις τελικές εξετάσεις συνειδητοποίησα ότι αυτός ο κατά.”</p>
										</div>
									</div>
								<!-- /.slide -->
								</div>
								<div class="slide">
									<div class="testimonial-box">
										<div class="author-infos">
											<span class="author-name">Anastasia Tsimxmcnvxv</span>
											<span class="author-job">Local Marketing ExeCUTIVE</span>
										</div>
										<div class="testimonial-text">
											<p>“Η συμμετοχή μου σε αυτό το σεμινάριο, υπήρξε κάτι παραπάνω από ικανοποιητική. Γνώρισα πολύ ενδιαφέροντες ανθρώπους, και καθηγητές που πραγματικά πολλές φορές υπερέβαλλαν εαυτό για να μας βοηθήσουν. Το αντικείμενο το προσέγγισα στην αρχή με δισταγμό, αλλά σχεδόν αμέσως κατάλαβα ότι είναι σίγουρα το marketing όχι μόνο του μέλλοντος, αλλά κυρίως του παρόντος...”</p>
										</div>
									</div>
								<!-- /.slide -->
								</div>
								<div class="slide">
									<div class="testimonial-box">
										<div class="author-infos">
											<span class="author-name">Anastasia Tsimxmcnvxv</span>
											<span class="author-job">Local Marketing ExeCUTIVE</span>
										</div>
										<div class="testimonial-text">
											<p>“Η συμμετοχή μου σε αυτό το σεμινάριο, υπήρξε κάτι παραπάνω από ικανοποιητική. Γνώρισα πολύ ενδιαφέροντες ανθρώπους, και καθηγητές που πραγματικά πολλές φορές υπερέβαλλαν εαυτό για να μας βοηθήσουν. Το αντικείμενο το προσέγγισα στην αρχή με δισταγμό, αλλά σχεδόν αμέσως κατάλαβα ότι είναι σίγουρα το marketing όχι μόνο του μέλλοντος, αλλά κυρίως του παρόντος. Ο όγκος των πληροφοριών που πήρα από αυτό το σεμινάριο είναι πραγματικά πολύ μεγάλος, αλλά και πάρα πολύ σημαντικός. Στο τέλος του σεμιναρίου, και καθώς προετοιμαζόμουν τόσο για τις ομαδικές εργασίες, όσο και για τις τελικές εξετάσεις συνειδητοποίησα ότι αυτός ο κατά.”</p>
										</div>
									</div>
								<!-- /.slide -->
								</div>
								<div class="slide">
									<div class="testimonial-box">
										<div class="author-infos">
											<span class="author-name">Anastasia Tsimxmcnvxv</span>
											<span class="author-job">Local Marketing ExeCUTIVE</span>
										</div>
										<div class="testimonial-text">
											<p>“Η συμμετοχή μου σε αυτό το σεμινάριο, υπήρξε κάτι παραπάνω από ικανοποιητική. Γνώρισα πολύ ενδιαφέροντες ανθρώπους, και καθηγητές που πραγματικά πολλές φορές υπερέβαλλαν εαυτό για να μας βοηθήσουν. Το αντικείμενο το προσέγγισα στην αρχή με δισταγμό, αλλά σχεδόν αμέσως κατάλαβα ότι είναι σίγουρα το marketing όχι μόνο του μέλλοντος, αλλά κυρίως του παρόντος...”</p>
										</div>
									</div>
								<!-- /.slide -->
								</div>
								<div class="slide">
									<div class="testimonial-box">
										<div class="author-infos">
											<span class="author-name">Anastasia Tsimxmcnvxv</span>
											<span class="author-job">Local Marketing ExeCUTIVE</span>
										</div>
										<div class="testimonial-text">
											<p>“Η συμμετοχή μου σε αυτό το σεμινάριο, υπήρξε κάτι παραπάνω από ικανοποιητική. Γνώρισα πολύ ενδιαφέροντες ανθρώπους, και καθηγητές που πραγματικά πολλές φορές υπερέβαλλαν εαυτό για να μας βοηθήσουν. Το αντικείμενο το προσέγγισα στην αρχή με δισταγμό, αλλά σχεδόν αμέσως κατάλαβα ότι είναι σίγουρα το marketing όχι μόνο του μέλλοντος, αλλά κυρίως του παρόντος. Ο όγκος των πληροφοριών που πήρα από αυτό το σεμινάριο είναι πραγματικά πολύ μεγάλος, αλλά και πάρα πολύ σημαντικός. Στο τέλος του σεμιναρίου, και καθώς προετοιμαζόμουν τόσο για τις ομαδικές εργασίες, όσο και για τις τελικές εξετάσεις συνειδητοποίησα ότι αυτός ο κατά.”</p>
										</div>
									</div>
								<!-- /.slide -->
								</div>
							</div>
						</div>
					</div>
				<!-- /.section-big-testimonials -->
				</section>--}}
                @if(isset($corporatebrands))
				<section class="section-trusted-us">
					<div class="container">
						<h2 class="section-title">Organizations trained by us</h2>
						<div class="logos-carousel-wrapper">
							<div class="logos-carousel owl-carousel">
                            @foreach ($corporatebrands as $key => $value)
                                    @if(isset($value['medias']))
                                    <?php //dd($value); ?>
								<div class="slide">
									<img alt="{{ $value['name'] }}" title="{{ $value['name'] }}" src="{{cdn(get_image($value['medias']))}}"/>
								</div>
								@endif
                            @endforeach
							</div>
						</div>
					</div>
				</section>
                @endif
			</main>
@endsection

@section('scripts')


<script>
    document.getElementById('header').classList.add('header-transparent');
</script>

<script>
/*@if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
$('.section-hero').css({background:url({{ $frontHelp->pImg($content, 'header-image') }})})
@endif*/

</script>


<script type="text/javascript">

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
//email function
$("#sendme1").click(function() {
var firstname = $("#cfirstname").val();
var surname = $("#csurname").val();
var company = $("#ccompany").val();
var job = $("#cjob").val();
var email = $("#cemail").val();
//var comment = $("#comment").val();
var tel = $("#ctel").val();
$("#returnmessage").empty(); // To empty previous error/success message.
// Checking for blank fields.
var thec = $('input#accept');
        if (thec.prop("checked") === false) {

          alert('Please accept our data privacy policy');

        }else {
if (email == '' || surname == '' || firstname == '' || company == ''  || tel == ''  || job == '') {
alert("Please Fill Required Fields");
} else {
// Returns successful data submission message when the entered information is stored in database.

$.ajax({
            type: "POST",
            url: "{{route('corporate')}}",

            data: {
//'cname': name,
'csurname': firstname + ' ' + surname,
'ccompany': company,
'cjob': job,
'cemail': email,
'ctel': tel
//'comment': comment
},
            success: function(data){
               // $("#contactUsReponse").html(data); // Append returned message to message paragraph.

			   var p = document.getElementById('coorporate-success').textContent = "Your message has been received, We will contact you soon.";
						var img = document.createElement('img');
						img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
						img.setAttribute('alt',"Info Alert" )

						$('#coorporate-success').append(img);

						$('.alert-outer').show()

$("#doall")[0].reset(); // To reset form fields on success.
//fbq('track', 'Lead');
            }
        });

}
        }
});

</script>

{{--<script> (function(){ window.ldfdr = window.ldfdr || {}; (function(d, s, ss, fs){ fs = d.getElementsByTagName(s)[0]; function ce(src){ var cs = d.createElement(s); cs.src = src; setTimeout(function(){fs.parentNode.insertBefore(cs,fs)}, 1); } ce(ss); })(document, 'script', 'https://sc.lfeeder.com/lftracker_v1_kn9Eq4RdQXY8RlvP.js'); })(); </script>--}}


@stop

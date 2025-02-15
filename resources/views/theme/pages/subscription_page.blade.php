@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@include('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status])
<main id="main-area" class="with-hero" role="main">
@if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
				<section class="section-hero" style="background-image:url({{ $frontHelp->pImg($content, 'header-image') }})">

					<div class="overlay"></div>
					<div class="container">
						<div class="hero-message pad-r-col-6">
							<h1>{{ $content->title }}</h1>
							<h2>{{ $content->subtitle }}</h2>
						</div>
					</div>
					</section>
				@else
						<section class="section-hero section-hero-small section-hero-blue-bg">
			<div class="container">
				<div class="hero-message">
					<h1>{{ $content->title }}</h1>
				</div>
			</div>
</section>
            @endif
				<section class="form-section">
					<div class="container">
						<div class="row">
							<div class="col6 col-sm-12">
								<div class="text-area">
								{!! $content->body !!}
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
					<?php
                           $title = '' ;
                           $body = '' ;
                           $cont = $content->titles()->where('category','benefits');

                           if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                 $title = $cont->first()->title;
                                 $body = $cont->first()->body;
                              }

                           ?>
						@if($title != '')<h2 class="section-title">{{$title}}</h2>@endif
						<div class="row row-flex row-flex-50">

							<?php $category = 'Grow your business';  $benefit = $content->benefit()->where('category',$category)->first();
								if (isset($benefit) && $benefit->title != '') : ?>
								<div class="col-4 col-sm-6 col-xs-12">
									<div class="icon-text-box">
										<div class="icon-wrapper">
											<img src="{{ cdn('/theme/assets/images/icons/knowcrunch-corporate-training-icon-01.svg') }}" alt=""/>
										</div>
										<h3>{{$benefit->title}}</h3>
										{!!$benefit->description!!}
									</div>
								<!-- /.col-4.col-sm-6 col-xs-12 -->
								</div>
							<?php endif ?>
							<?php $category = 'Learn from the best';  $benefit = $content->benefit()->where('category',$category)->first();
								if (isset($benefit) && $benefit->title != '') : ?>
								<div class="col-4 col-sm-6 col-xs-12">
									<div class="icon-text-box">
										<div class="icon-wrapper">
											<img src="{{ cdn('/theme/assets/images/icons/knowcrunch-corporate-training-icon-01.svg') }}" alt=""/>
										</div>
										<h3>{{$benefit->title}}</h3>
										{!!$benefit->description!!}
									</div>
								<!-- /.col-4.col-sm-6 col-xs-12 -->
								</div>
							<?php endif ?>
							<?php $category = 'Promote Leadership';  $benefit = $content->benefit()->where('category',$category)->first();
								if (isset($benefit) && $benefit->title != '') : ?>
								<div class="col-4 col-sm-6 col-xs-12">
									<div class="icon-text-box">
										<div class="icon-wrapper">
											<img src="{{ cdn('/theme/assets/images/icons/knowcrunch-corporate-training-icon-01.svg') }}" alt=""/>
										</div>
										<h3>{{$benefit->title}}</h3>
										{!!$benefit->description!!}
									</div>
								<!-- /.col-4.col-sm-6 col-xs-12 -->
								</div>
							<?php endif ?>
							<?php $category = 'Inspire your executives';  $benefit = $content->benefit()->where('category',$category)->first();
								if (isset($benefit) && $benefit->title != '') : ?>
								<div class="col-4 col-sm-6 col-xs-12">
									<div class="icon-text-box">
										<div class="icon-wrapper">
											<img src="{{ cdn('/theme/assets/images/icons/knowcrunch-corporate-training-icon-01.svg') }}" alt=""/>
										</div>
										<h3>{{$benefit->title}}</h3>
										{!!$benefit->description!!}
									</div>
								<!-- /.col-4.col-sm-6 col-xs-12 -->
								</div>
							<?php endif ?>
							<?php $category = 'Be competitive';  $benefit = $content->benefit()->where('category',$category)->first();
								if (isset($benefit) && $benefit->title != '') : ?>
								<div class="col-4 col-sm-6 col-xs-12">
									<div class="icon-text-box">
										<div class="icon-wrapper">
											<img src="{{ cdn('/theme/assets/images/icons/knowcrunch-corporate-training-icon-01.svg') }}" alt=""/>
										</div>
										<h3>{{$benefit->title}}</h3>
										{!!$benefit->description!!}
									</div>
								<!-- /.col-4.col-sm-6 col-xs-12 -->
								</div>
							<?php endif ?>
							<?php $category = 'Thrive';  $benefit = $content->benefit()->where('category',$category)->first();
								if (isset($benefit) && $benefit->title != '') : ?>
								<div class="col-4 col-sm-6 col-xs-12">
									<div class="icon-text-box">
										<div class="icon-wrapper">
											<img src="{{ cdn('/theme/assets/images/icons/knowcrunch-corporate-training-icon-01.svg') }}" alt=""/>
										</div>
										<h3>{{$benefit->title}}</h3>
										{!!$benefit->description!!}
									</div>
								<!-- /.col-4.col-sm-6 col-xs-12 -->
								</div>
							<?php endif ?>
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
                                    @if(isset($value['image']))
								<div class="slide">
                                    <?php //dd($value); ?>
									<img alt="{{ $value['title'] }}" title="{{ $value['title'] }}" src="{{ $value['image'] }}"/>
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


{{--<script> (function(){ window.ldfdr = window.ldfdr || {}; (function(d, s, ss, fs){ fs = d.getElementsByTagName(s)[0]; function ce(src){ var cs = d.createElement(s); cs.src = src; setTimeout(function(){fs.parentNode.insertBefore(cs,fs)}, 1); } ce(ss); })(document, 'script', 'https://sc.lfeeder.com/lftracker_v1_kn9Eq4RdQXY8RlvP.js'); })(); </script>--}}


@stop

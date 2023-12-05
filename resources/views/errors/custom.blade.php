@extends('errors.master')

@section('title')
<title>Error</title>
@stop

@section('message_page')
<main id="main-area" class="no-pad-top" role="main">
				<section class="section-text-img-blue">
					<div class="container">
						<div class="columns-wrapper">
							<div class="row row-flex">
								<div class="col-7 col-sm-12">
									<div class="text-area">
										<h1>Error</h1>
										<h2>{{ $message }}</h2>
										<p>Here are some helpful links instead: </p>
										<p><a href="/" class="dark-bg">Homepage</a><br/>
										<a href="/in-class-courses" class="dark-bg">In-class courses</a><br/>
										<a href="/e-learning-courses" class="dark-bg">E-learning courses</a><br/>
										<a href="/contact" class="dark-bg">Contact us</a></p>
									</div>
								</div>
								<div class="col-5 col-sm-12">
									<div class="icon-wrapper">
										<img src="{{cdn('/theme/assets/images/icons/404-page.svg')}}" alt="Error 404"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
            </main>
@stop

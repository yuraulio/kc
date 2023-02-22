@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<main id="main-area" class="no-pad-top" role="main">
				<section class="section-text-img-blue">
					<div class="container">
						<div class="columns-wrapper">
							<div class="row row-flex">
								<div class="col-7 col-sm-12">
									<div class="text-area">
										<h1>Page not found</h1>
										<h2>Error code: 404. This page was moved, renamed or doesn’t exist.</h2>
										<p>Here are some helpful links instead:</p>
										<ul>
											<li><a href="/" class="dark-bg">Homepage</a></li>
											<li><a href="/in-class-courses" class="dark-bg">In-class courses</a></li>
											<li><a href="/e-learning-courses" class="dark-bg">E-learning courses</a></li>
											<li><a href="/contact" class="dark-bg">Contact us</a></li>
										</ul>
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

@extends('theme.layouts.master')
@section('metas')

    <title>{{ $page['name'] }}</title>
   {!! $page->metable->getMetas() !!}

@endsection
@section('content')
@include('theme.preview.preview_warning', ["id" => $page['id'], "type" => "content", "status" => $page['status']])
<main id="" role="main" class="about-static-page">
<script type="application/ld+json">
                {
                    "@context": "http://schema.org",
                    "@type": "AboutPage",
                    "name": "{{ $page['name'] }}",
                    "description":"{!! $page['content'] !!}",
                    "breadcrumb": "Home > About us"
                }
                </script>

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
      <!-- /.section-hero -->
   </section>
   @endif
   <section class="section-page-content">
      <div class="container">
         <div class="content-text-area">
            {!! $page['content'] !!}
         </div>
         <!-- /.text-area -->
      </div>
      <!-- /.container -->
   </section>
   <!-- /.section-page-content -->
</main>
@endsection
@section('scripts')
<script>
    document.getElementById('header').classList.add('header-transparent');
</script>
@stop

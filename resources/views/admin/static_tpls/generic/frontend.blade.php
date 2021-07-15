@extends('theme.layouts.master')
@section('content')
@include('theme.preview.preview_warning', ["id" => $page['id'], "type" => "content", "status" => $page['status']])
<main id="" role="main">
   <script type="application/ld+json">
      {
          "@context": "http://schema.org",
          "@type": "WebPage",
          "name": "Logos",
          "description": "Media saying great things about us",
          "breadcrumb": "Home > Logos"
      }
   </script>
   <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
            <h1>{{ $page['name'] }}</h1>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
   <section class="section-page-content logos-sections">
      <div class="container">

      <?php

         $url = explode('/',request()->url());
         $url = (end($url));

         $class = 'logos-area';
         if(trim($url) == 'terms' || trim($url) == 'data-privacy-policy'){
            $class = 'logos-area terms';
         }



      ?>

         <div class="{{$class}} content-text-area">

             {!! $page['content'] !!}
             <div class="logos-area-wrapper">
            <div class="row row-flex">
               @if($page['id'] == 800 && isset($brands))
                  @foreach ($brands as $key => $value)
                     @if(isset($value['image']))
                     <div class="col-3 col-sm-4 col-xs-6 self-align-center logo-column">
                        <a class="logo-img-wrapper" target="{{ $value['target'] }}" rel="nofollow" href="{{ $value['ext_url'] }}" title="{{ $value['title'] }}">
                        <img   alt="{{ $value['title'] }}" title="{{ $value['title'] }}" src="{{ $value['image'] }}" />
                        </a>
                     </div>
                     @endif
                  @endforeach
               @elseif($page['id'] == 801 && isset($logos))
                     @foreach ($logos as $key => $value)
                        @if(isset($value['image']))
                        <div class="col-3 col-sm-4 col-xs-6 self-align-center logo-column">
                           <a  class="logo-img-wrapper" target="{{ $value['target'] }}" href="{{ $value['ext_url'] }}" title="{{ $value['title'] }}">
                           <img   alt="{{ $value['title'] }}" title="{{ $value['title'] }}" src="{{ $value['image'] }}" />
                           </a>
                        </div>
                        @endif
                     @endforeach

               @endif
               </div>
            </div>
         </div>
      </div>
      <!-- /.container -->
   </section>
   <!-- /.section-page-content -->
</main>
@endsection
@section('scripts')
@stop


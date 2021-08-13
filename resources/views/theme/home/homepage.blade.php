@extends('theme.layouts.master')
@section('content')
<?php
   if(Session::has('scopeone')){
       $fone = Session::get('scopeone');
   }
   else { $fone = 0; }

   $totalfound = 0;
   $totalcats = 0;
   ?>
<main id="main-area" class="with-hero" role="main">
   <?php if (isset($homePage) && count($homePage) > 0) : ?>
   <?php $image = get_image($homePage['mediable'],'header-image');
     // dd($image);
   ?>
   <section class="section-hero"  style="background-image:url('{{ $image }}');">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message">
            <h1>{{ $homePage['title'] }}</h1>
            <h2>{!! $homePage['content'] !!}</h2>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
   <?php


      endif; ?>


   @if(isset($nonElearningEvents))
   @foreach($nonElearningEvents as $bcatid => $category)

   <section class="section-text-carousel event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">

                  <h2>{{$category['name']}}</h2>
                  @if ($category['description'] != '')
                  @if($category['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{!!$category['hours']!!}</span>@endif
                  @endif
                  <p>{!!$category['description']!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">

                     @foreach($category['events'] as $key => $row)

                     <div class="slide">
                        <?php
                           $string = $row['title'];
                           if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                           }
                           else {
                               $until = $string;
                           }
                        ?>

                        @if (isset($row['mediable']))
                        <a href="{{ $row['slugable']['slug'] }}"><img src="{{ cdn(get_image($row['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
                        @endif

                        <?php //dd($row->slugable['slug']); ?>

                        <div class="box-text">
                           @if(isset($row['slugable']) && $row['slugable']['slug'] != '')
                              <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>
                           @endif
                           @if(isset($row['city']) && count($row['city']) > 0)

                           <a href="{{ $row['city'][0]['slugable']['slug'] }}" class="location">{{ $row['city'][0]['name'] }}</a>
                           @endif
                              <span class="date">{{date('F Y', strtotime($row['published_at']))}} </span>
                           @if(isset($row['slugable']) && $row['slugable']['slug'] != '')

                              @if($row['status'] != 0)
                              <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                              @else
                              <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
                              @endif
                           @endif

                        </div>
                     </div>


                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-text-carousel.section--blue-gradient -->
   </section>

   @endforeach
   @endif

   @if(!empty($elearningEvents))
   @foreach($elearningEvents as $bcatid => $category)

   <section class="section-text-carousel background section--blue-gradient">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">

                  <h2>{{ $category['name'] }}</h2>

                    @if ($category['description'] != '')
                        @if($category['hours'])
                            <span class="duration"><img src="{{ cdn('/theme/assets/images/icons/Start-Finish.svg')}}" class="replace-with-svg" alt=""/>{{ $category['hours'] }}</span>
                        @endif
                    @endif
                    <p>{!!$category['description']!!}</p>
               </div>
            </div>
            <?php //dd($events[0]); ?>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = '';

                     ?>
                     @foreach($category['events'] as $key => $row)


                     <div class="slide">
                        <?php
                        //dd($row);
                            $string = $row['title'];
                            if( strpos($string, ',') !== false ) {
                                $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                                $until = $string;

                            }
                            //dd($until);
                        ?>
                        <?php //var_dump($until) ?>
                        @if (isset($row['mediable']))
                        <a href="{{ $row['slugable']['slug'] }}"><img src="{{ cdn(get_image($row['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
                        @endif
                        <div class="box-text">
                           <?php
                            $string = $row['title'];
                            if( strpos($string, ',') !== false ) {
                                $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                                $until = $string;
                            }
                            ?>


                            <?php
                            if(isset($row['slugable'])){
                                $slug = $row['slugable']['slug'];
                            }else{
                                $slug = '';
                            }

                            $month = date('F Y', strtotime($row['published_at']));

                             ?>
                           <?php $url = url($slug); ?>

                           <h3><a href="{{$url}}">{{ $until }}</a></h3>
                           <a href="{{ $category['slug'] }}" class="location">{{ $category['name'] }}</a>
                           <span class="date"> </span>
                           <a href="{{$url}}" class="btn btn--sm btn--secondary">course details</a>

                        </div>
                     </div>


                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   @endforeach
   @endif

<?php //dd('end'); ?>
<?php //dd($inclassEventsbycategoryFree); ?>
   @if(isset($inclassFree))
   @foreach($inclassFree as $bcatid => $category)
   <?php
   //dd($bcateventids[]);
    ?>

   <?php //dd($bcateventids); ?>
   <section class="section-text-carousel background event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">


                  <h2>{{$category['name']}}</h2>
                  @if ($category['description'] != '')
                  @if($category['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{{ $category['hours'] }}</span>@endif
                  @endif
                  <p>{!!$category['description']!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     @foreach($category['events'] as $key => $row)

                     <div class="slide">
                     <?php
                           $string = $row['title'];
                            if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                               $until = $string;
                               }

                               //dd($row);
                               ?>
                        @if (isset($row['mediable']))
                        <a href="{{ $row['slugable']['slug'] }}"><img src="{{ cdn(get_image($row['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
                        @endif

                        <div class="box-text">
                           <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>
                           <span class="date"></span>
                           @if($row['view_tpl'] == 'event_free')
                           <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                           @elseif($row['view_tpl'] == 'event_free_coupon')
                           <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
                           @endif

                        </div>
                     </div>

                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-text-carousel.section--blue-gradient -->
   </section>


   @endforeach
   @endif


   @if(isset($elearningFree))
   @foreach($elearningFree as $bcatid => $category)
   <?php //dd($bcateventids); ?>

   <section class="section-text-carousel background event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">

                  <h2>{{$category['name']}}</h2>
                  <?php //dd($until); ?>
                  @if ($category['description'] != '')
                  @if($category['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{{ $category['hours'] }}</span>@endif
                  @endif
                  <p>{!!$category['description']!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     @foreach($category['events'] as $key => $row)


                     <div class="slide">
                     <?php

                           $string = $row['title'];
                            if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                               $until = $string;
                               }

                               //dd($row);

                               ?>
                        @if (isset($row['mediable']))
                        <a href="{{ $row['slugable']['slug'] }}"><img src="{{ cdn(get_image($row['mediable'],'event-card')) }}" alt="{{ $until}}"/></a>
                        @endif

                        <div class="box-text">
                           <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>

                           <a href="{{$category['slug']}}" class="location">{{ $category['name'] }}</a>
                           <span class="date"></span>
                           <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                        </div>
                     </div>

                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-text-carousel.section--blue-gradient -->
   </section>


   @endforeach
   @endif

   <section class="section--partners hidden-xs">
      <div class="container container--md">
      <?php //dd($homeBrands); ?>
         @if(isset($homeBrands) && count($homeBrands) > 0)

         <h2 class="section-title">Knowcrunch is trusted by hundreds of companies</h2>

         <div class="row row-flex row-flex-0">
            <?php
               //$rand_keys = array_rand($homeBrands, 6);
               //   echo print_r($rand_keys);
               //  echo print_r($homeBrands[7]['image']);

               ?>
            @foreach ($homeBrands as $key)
            <?php //dd($key);
                if($key['medias'] != null){
                   // dd($key->medias);
                   $path = url($key['medias']['path'].$key['medias']['original_name']);
                   //dd($path);
                }else{
                    $path = '';
                }
             ?>
            <div class="col">
               <div class="partners-set">
                  <img alt="{{ $key['name'] }}" title="{{ $key['name'] }}" src="{{ $path }}" width="150" height="77" align=""/>
               </div>
            </div>
            @endforeach
         </div>
         <div class="view-more">
            <a href="/they-trust-us">See more</a>
         </div>
         @endif
         <hr>
         @if(isset($homeLogos) && isset($homeLogos[0]))
         <h2 class="section-title">Knowcrunch mentioned in the media</h2>
         <div class="row row-flex row-flex-0">



            @foreach ($homeLogos as $key)
            <?php
               if($key['medias'] != null){
                $path = url($key['medias']['path'].$key['medias']['original_name']);
             }else{
                 $path = '';
             }
             ?>

            <div class="col">
               <div class="partners-set">
               <img alt="{{ $key['name'] }}" title="{{ $key['name'] }}" src="{{ $path }}" width="150" height="77" align=""/>
               </div>
            </div>
            @endforeach
         </div>
         <div class="view-more">
            <a href="/regularly-mentioned-in-media">See more</a>
         </div>
         @endif
      </div>
   </section>
</main>
@stop

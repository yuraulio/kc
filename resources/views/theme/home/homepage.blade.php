@extends('theme.layouts.master')
@section('content')
{{--@inject('frontHelp', 'Library\FrontendHelperLib')--}}
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
    

   @if(isset($eventsbycategory))
   @foreach($eventsbycategory as $bcatid => $bcateventids)

   <section class="section-text-carousel event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">
                  <?php
                     /*$string = $frontHelp->pField($eventsbycategoryDetailsHelper[$bcatid], 'title');
                      if( strpos($string, ',') !== false ) {
                        $until = substr($string, 0, strrpos($string, ","));
                      }
                      else {
                         $until = $string;
                         }*/
                         $until = $bcateventids['cat']['name'];
                         ?>
                  <?php
                     $location = [];
                     $eventtype = [];
                     $eventtopic = [];
                     $advancedtag = 0;
                     $advancedtagslug = '';
                     $categoryType=false;
                   
                     ?>
                  <h2>{{$until}}</h2>
                  @if (isset($bcateventids['cat']['description']) && $bcateventids['cat']['description'] != '')
                  @if(isset($bcateventids) && $bcateventids['cat']['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{!!$bcateventids['cat']['hours']!!}</span>@endif
                  @endif
                  <p>{!!$bcateventids['cat']['description']!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     <?php //dd('asd'); ?>
                     @foreach($bcateventids['events'] as $key => $row)
                     <?php //dd($row->view_tpl); ?>
                     {{--@if($row['view_tpl'] != 'elearning_free' && $row['view_tpl'] != 'event_free' && $row['view_tpl'] != 'event_free_coupon')--}}
                     <?php
                     //dd($row);
                        $location = [];
                        $eventtype = [];
                           $eventtopic = [];
                           $advancedtag = 0;
                           $onthiscat = 0;
                           $advancedtagslug = '';
                           //dd($row);
                        if (isset($row['category']) && !empty($row['category'])) :
                        foreach ($row['category'] as $category) :
                            $onthiscat=$category['id'];
                            $eventtopic[]=$category['id'];


                                    if ($category['id'] == 117) {
                                        $advancedtag = 1;
                                        $advancedtagslug = $category['slug'];
                                    }
                        endforeach;
                        endif;

                        $dont = true;



                           $totalfound++;
                           $chmonth = date('m', strtotime($row['published_at']));
                        $month = date('F Y', strtotime($row['published_at']));
                        if($chmonth != $lastmonth) {
                        $lastmonth = $chmonth;
                        }
                           ?>
                     @if($onthiscat == $bcatid)
                     <?php //dd('asdtest'); ?>
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
                        @if (isset($row['media']) && !empty($row['media']) && $row['media']['name'] != '')
                        <a href="{{ $row['slugable']['slug'] }}"><img src="{{ $row['media']['path'] }}/{{$row['media']['name']}}-event-card{{$row['media']['ext']}}}}" alt="{{ $until}}"/></a>
                        @endif

                        <?php //dd($row->slugable['slug']); ?>

                        <div class="box-text">
                        @if(isset($row['slugable']) && $row['slugable']['slug'] != '')
                           <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>
                        @endif

                           {{--<a href="{{$categoryType['slug']}}" class="location">{{ $categoryType['name'] }}</a>--}}
                           <span class="date"> </span>
                        @if(isset($row['slugable']) && $row['slugable']['slug'] != '')
                        <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
                        @endif

                        </div>
                     </div>
                     @endif
                     {{--@endif--}}
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

   <?php //dd($eventsbycategoryElearning); ?>
   @if(!empty($eventsbycategoryElearning))
   @foreach($eventsbycategoryElearning as $bcatid => $bcateventids)
 
   <section class="section-text-carousel background section--blue-gradient">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">
                  <?php
              
                        $until = $bcateventids['cat']['name'];
                         ?>
                  <h2>{{ $until }}</h2>
                  <?php
                     $location = [];
                     $eventtype = [];
                     $eventtopic = [];
                     $advancedtag = 0;
                     $advancedtagslug = '';
                    //  if (isset($eventsbycategoryDetailsHelper[$bcatid]->categories) && !empty($eventsbycategoryDetailsHelper[$bcatid]->categories)) :
                        if (isset($eventsbycategory1) && !empty($eventsbycategory1)) :
                        //  foreach ($eventsbycategoryDetailsHelper[$bcatid]->categories as $category) :
                            foreach ($eventsbycategory1 as $category) :
                            

                             if ($category['id'] == 117) {
                                 $advancedtag = 1;
                                 $advancedtagslug = $category['slug'];
                             }

                         endforeach;
                     endif;
                     ?>
                     <?php //dd($bcateventids); ?>
                    @if (isset($bcateventids['cat']['description']) && $bcateventids['cat']['description'] != '')
                        @if(isset($bcateventids['id']) && $bcateventids['cat']['hours'])
                            <span class="duration"><img src="{{ cdn('/theme/assets/images/icons/Start-Finish.svg')}}" class="replace-with-svg" alt=""/>{{ $bcateventids['cat']['hours'] }}</span>
                        @endif
                    @endif
                    <p>{!!$bcateventids['cat']['description']!!}</p>
               </div>
            </div>
            <?php //dd($events[0]); ?>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; 
                        
                     ?>
                     @foreach($bcateventids['events'] as $key => $row)
                    
                     @if($row['view_tpl'] != 'event_free' && $row['view_tpl'] != 'event_free_coupon')
                    <?php
                    
                        $location = [];
                        $eventtype = [];
                        $eventtopic = [];
                        $advancedtag = 0;
                        $onthiscat = 0;
                        $advancedtagslug = '';
                        $categoryEvent = false;

                        //dd($row->category);
                        if (isset($row['category']) && !empty($row['category'])) :
                           
                            foreach ($row['category'] as $category) :
                                if(count($row['city'])>0){
                                    $location=$row['city'][0];
                                }else{
                                    $location = null;
                                }

                                $eventtype=$category;
                                $eventtopic[]=$category['id'];
                                $onthiscat=$category['id'];
                                ////////////EDWWWWWWWWWW ******************
                                if ($category['id'] == 117) {
                                    $advancedtag = 1;
                                    $advancedtagslug = $category['slug'];
                                }
                                ////////////EDWWWWWWWWWW ****************
                            endforeach;
                        endif;

                        //dd($location);

                        $dont = true;

                        $totalfound++;
                        $chmonth = date('m', strtotime($row['published_at']));
                        $month = date('F Y', strtotime($row['published_at']));

                        if($chmonth != $lastmonth) {
                            $lastmonth = $chmonth;
                        }
                    ?>
                    <?php
                           //dd($location);
                           //dd($bcatid)
?>
                     @if($onthiscat == $bcatid)

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
                        @if (isset($row['media']) && !empty($row['media']) && !empty($row['media']['path']))

                            <a href="{{ $row['slugable']['slug'] }}"><img src="{{ $row['media']['path'] }}/{{$row['media']['name']}}-event-card{{$row['media']['ext']}}}}" alt="{{$until}}"/></a>
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

                           //dd($row->ticket);
                              if (isset($row['ticket']) && count($row['ticket']) > 0) {
                                  //dd($row->ticket[0]);
                                      $price = $row['ticket'][0]['pivot']['price'];
                                      //dd($price);
                              }
                              else { $price = 0; }
                              ?>
                           @if(isset($row['status']))
                            <?php
                                if($row['status'] == 0){
                                        $etstatus = 0;
                                }else if($row['status'] == 1){
                                        $etstatus = 1;
                                }else if($row['status'] == 2){
                                        $etstatus = 2;
                                }else{
                                        $etstatus = 3;
                                }
                                //dd($etstatus);
                            ?>
                           @endif
                           <?php //var_dump($row); ?>

                            <?php
                            if(isset($row['slugable'])){
                                $slug = $row['slugable']['slug'];
                            }else{
                                $slug = '';
                            }
                             ?>
                           <?php $url = url($slug); ?>

                           <h3><a href="{{$url}}">{{ $until }}</a></h3>

                           <?php //dd($location); ?>

                           @if($location != null && isset($location))
                           <?php //dd(count($location) >0); ?>

                              <a href="{{ $location['slugable']['slug'] }}" class="location">{{ $location['name'] }}</a>

                           @else
                            <!-- EDWWWWWWWWWWWWWWWWWW ******************** -->
                            {{--<a href="{{$categoryType['slug']}}" class="location">{{ $categoryType['name'] }}</a>--}}
                           @endif
                           <span class="date"> {{$month}}</span>

                           <?php

                           //dd($row->slugable['slug']);
                           if(isset($row['status'])){
                                $estatus = $row['status'];
                               

                              if($price == 0 && $estatus == 1){
                                 $estatus = 2;
                                 //dd($estatus);
                              }
                              //dd($estatus);

                              switch ($estatus) {
                              	case 1:
                              		//'Closed'
                              		?>
                           <a href="{{ $slug }}" class="btn btn--sm btn--secondary">course details</a>
                           <?php
                              break;
                              case 1:
                              	//'CLOSED'
                              ?>                            <a href="{{ $slug }}" class="btn btn--sm btn--secondary btn--completed">closed</a>
                           <?php
                              break;
                              case 2:
                              	//'SOLD-OUT'
                              ?>                            <a href="{{ $slug }}" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                           <?php
                              break;
                              case 3:
                              	//'COMPLETED'
                              ?>                            <a href="{{ $slug }}" class="btn btn--sm btn--secondary btn--sold-out">course details</a>
                           <?php
                              break;
                              default:
                              ?>
                           <a href="{{ $slug }}" class="btn btn--sm btn--secondary">course details</a>
                           <?php
                              break;
                              }

                           }else{
                               ?>
                           <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
                           <?php
                           }
                           ?>
                        </div>
                     </div>
                     @endif
                     @endif
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
   @if(isset($inclassEventsbycategoryFree))
   @foreach($inclassEventsbycategoryFree as $bcatid => $bcateventids)
   <?php
   //dd($bcateventids[]);
    ?>
   
   <?php //dd($bcateventids); ?>
   <section class="section-text-carousel background event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">
                  <?php
                     /*$string = $frontHelp->pField($eventsbycategoryDetailsHelperFree[$bcatid], 'title');
                      if( strpos($string, ',') !== false ) {
                        $until = substr($string, 0, strrpos($string, ","));
                      }
                      else {
                         $until = $string;
                         }*/
                         //dd($bcateventids);
                         $until = $bcateventids['cat']['name'];
                         ?>
                  <?php
                     $location = [];
                     $eventtype = [];
                     $eventtopic = [];
                     $advancedtag = 0;
                     $advancedtagslug = '';
                     $categoryType= false;

                  
                     ?>
                  <h2>{{$until}}</h2>
                  @if (isset($bcateventids['cat']['description']) && $bcateventids['cat']['description'] != '')
                  @if(isset($bcateventids) && $bcateventids['cat']['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{{ $bcateventids['cat']['hours'] }}</span>@endif
                  @endif
                  <p>{!!$bcateventids['cat']['description']!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     @foreach($bcateventids['events'] as $key => $row)
                     @if($row['view_tpl'] == 'event_free' || $row['view_tpl'] == 'event_free_coupon')
                     <?php
                        $location = [];
                        $eventtype = [];
                        $eventtopic = [];
                        $advancedtag = 0;
                        $onthiscat = 0;
                        $advancedtagslug = '';
                        $hours ='';
                        if (isset($row['category']) && !empty($row['category'])) :
                            foreach ($row['category'] as $category) :
                                $onthiscat=$category['id'];
                                $eventtopic[]=$category['id'];


                                        if ($category['id'] == 117) {
                                            $advancedtag = 1;
                                            $advancedtagslug = $category['slug'];
                                        }
                            endforeach;
                            endif;

                        $dont = true;



                           $totalfound++;
                           $chmonth = date('m', strtotime($row['published_at']));
                        $month = date('F Y', strtotime($row['published_at']));
                        if($chmonth != $lastmonth) {
                        $lastmonth = $chmonth;
                        }
                           ?>
                     @if($onthiscat == $bcatid)
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
                        @if (isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                        <a href="{{ $row['slugable']['slug'] }}"><img src="{{ $row['media']['path'] }}/{{$row->media['name']}}-event-card{{$row['media']['ext']}}}}" alt="{{ $until}}"/></a>
                        @endif

                        <div class="box-text">
                           <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>

                           {{--<a href="{{$categoryType['slug']}}" class="location">{{ $categoryType['name'] }}</a>--}}
                           <span class="date"></span>
                           @if($row['view_tpl'] == 'event_free')
                           <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                           @elseif($row['view_tpl'] == 'event_free_coupon')
                           <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">course details</a>
                           @endif

                        </div>
                     </div>
                     @endif
                     @endif
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-text-carousel.section--blue-gradient -->
   </section>
   {{--@endif--}}

   @endforeach
   @endif

   <?php //dd($eventsbycategoryFree); ?>

   @if(isset($eventsbycategoryFree))
   @foreach($eventsbycategoryFree as $bcatid => $bcateventids)
   <?php //dd($bcateventids); ?>
   @if(isset($bcateventids['cat']))
   <section class="section-text-carousel background event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">
               <?php
    //dd($bcateventids);
   ?>
                  <?php
                    
                         $until = $bcateventids['cat']['name'];
                         ?>
                  <?php
                     $location = [];
                     $eventtype = [];
                     $eventtopic = [];
                     $advancedtag = 0;
                     $advancedtagslug = '';
                     $categoryType= false;
                  
                     ?>
                  <h2>{{$until}}</h2>
                  <?php //dd($until); ?>
                  @if (isset($bcateventids['cat']) && $bcateventids['cat']['description'] != '')
                  @if($bcateventids['cat']['hours'])<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{{ $bcateventids['cat']['hours'] }}</span>@endif
                  @endif
                  <p>{!!$bcateventids['cat']['description']!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     @foreach($bcateventids['events'] as $key => $row)
                     @if($row['view_tpl'] == 'elearning_free')
                     <?php
                     //dd('asd');
                        $location = [];
                        $eventtype = [];
                           $eventtopic = [];
                           $advancedtag = 0;
                           $onthiscat = 0;
                           $advancedtagslug = '';
                           $hours ='';
                           if (isset($row['category']) && !empty($row['category'])) :
                            foreach ($row['category'] as $category) :
                                $onthiscat=$category['id'];
                                $eventtopic[]=$category['id'];


                                        if ($category['id'] == 117) {
                                            $advancedtag = 1;
                                            $advancedtagslug = $category['slug'];
                                        }
                            endforeach;
                            endif;

                        $dont = true;



                           $totalfound++;
                           $chmonth = date('m', strtotime($row['published_at']));
                        $month = date('F Y', strtotime($row['published_at']));
                        if($chmonth != $lastmonth) {
                        $lastmonth = $chmonth;
                        }
                           ?>
                     @if($onthiscat == $bcatid)
                     <?php //dd('asd'); ?>
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
                        @if (isset($row['medias']) && $row->medias['name'] != '')
                        <a href="{{ $row['slugable']['slug'] }}"><img src="{{ $row->medias['path'] }}/{{$row->medias['name']}}-event-card{{$row->medias['ext']}}}}" alt="{{ $until}}"/></a>
                        @endif

                        <div class="box-text">
                           <h3><a href="{{ $row['slugable']['slug'] }}">{{ $until}}</a></h3>

                           {{--<a href="{{$categoryType['slug']}}" class="location">{{ $categoryType['name'] }}</a>--}}
                           <span class="date"></span>
                           <a href="{{ $row['slugable']['slug'] }}" class="btn btn--sm btn--secondary">enroll for free</a>
                        </div>
                     </div>
                     @endif
                     @endif
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-text-carousel.section--blue-gradient -->
   </section>
   @endif

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

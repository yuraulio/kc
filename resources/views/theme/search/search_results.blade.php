@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<main id="main-area" class="search-results-page-main"  role="main">
<script type="application/ld+json">
                {
                    "@context": "http://schema.org/",
                    "@type": "SearchResultsPage",
                    "name": "Search results",
                    "description":"Search results",
                    "potentialAction": {
                        "@type": "SearchAction",
                        "target": "http://knowcrung.com/search?s={s}",
                        "query-input": "required name=search_term"
                    }
                }
                </script>
   <div class="container">
   <div class="search-results-head">
      <h1 class="search-results-title">Search results for <span>{{ $search_term }}</span></h1>
      <?php
         $results = 0;
         
         if(!empty($list)){
             $results += count($list);
         }
         /*
         if(isset($instructors)){
             $results += count($instructors);
         }*/
         
         ?>
      @if($results > 0 )
      <p class="search-results-text"><span>{{$results}} result(s) </span> found containing the term <span>{{ $search_term }}.</span></p>
      @else
      <p class="search-results-text"><strong>{{$results}} result(s) </strong> were found containing the term <strong>{{ $search_term }}</strong>. Try again.</p>
      @endif
   </div>
   <!-- /.search-results-heading -->
   @if($results > 0)
   <div class="search-results-wrapper">
   <?php echo empty($list)?>
   @if (count($list) > 0)
      <div class="section section--dynamic-learning">
        
         @foreach($list as $key => $row)
         @if($row->view_tpl != 'elearning_event' && $row->view_tpl != 'elearning_greek')
         <?php
            
                $chmonth = date('m', strtotime($row->published_at));
                $month = date('F Y', strtotime($row->published_at));
         
                
                $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                    return $cartItem->id === $row->id;
                });
                
                ?>
         <?php
            $location = [];
            $eventtype = [];
            $advancedtag = 0;
            if (isset($row->categories) && !empty($row->categories)) :
                foreach ($row->categories as $category) :
                    if ($category->depth != 0 && $category->parent_id == 9) {
                         $location=$category;
                    }
                    if ($category->depth != 0 && $category->parent_id == 12) {
                         $eventtype=$category;
                    }
                    if ($category->id == 117) {
                        $advancedtag = 1;
                        $advancedtagslug = $category->slug;
                    }
                endforeach;
            endif;
            ?>

            <?php

            $loc = '';
            
            if(isset($location->name)){
               $loc = $location->name;
            }

            
            $search1 = strpos(trim(strtolower($loc)),trim(strtolower($search_term)));
            $search2 = strpos(trim(strtolower($frontHelp->pField($row, 'title'))),trim(strtolower($search_term)));
           // $search3 = strpos(trim(strtolower($lvalue['header'])),trim(strtolower($search_term)));
           // $search4 = false;


            ?>

         <!-- ./dynamic-learning--subtitle -->
         <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
            <div class="item">
               <div class="left">
                  <h2 class="@if($search2!==false) search-highlight @endif">{{ $frontHelp->pField($row, 'title') }}</h2>
                  <div class="bottom">
                     @if(isset($location->name))   <a href="{{ $location->slug }}" class="location" title="{{ $location->name }}">
                     <img width="20" src="/theme/assets/images/icons/marker.svg" alt=""> <span class="@if($search1!==false) search-highlight @endif"> {{ $location->name }} </span></a> @else City @endif
                     <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt="">@if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '') {{ $row['c_fields']['simple_text'][0]['value'] }} @else Date @endif</div>
                     <div class="expire-date"><img width="20" src="/theme/assets/images/icons/Times.svg" alt="">{{ $row['c_fields']['simple_text'][34]['value'] }}h</div>
                  </div>
               </div>
               <div class="right">
                  <?php  if (isset($eventprices[$row->id])) {
                     $price = $eventprices[$row->id];												
                     
                     }
                     else { $price = 0; } ?>

                  @if($row->view_tpl == 'elearning_pending')
                     <div class="price">Pending</div>
                  @else
                     <div class="price">from €{{$price}}</div>
                  @endif
                  <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Course Details</a>
               </div>
            </div>
            <!-- ./item -->
            @else
            <!-- ./dynamic-learning--subtitle -->
            <div class="dynamic-courses-wrapper">
               <div class="item">
                  <div class="left">
                     <h2>{{ $frontHelp->pField($row, 'title') }}</h2>
                     <div class="expire-date"><img width="20" src="/theme/assets/images/icons/Times.svg" alt="">{{ $row['c_fields']['simple_text'][34]['value'] }}h</div>
                  </div>
                  <div class="right">
                     <?php  if (isset($eventprices[$row->id])) {
                        $price = $eventprices[$row->id];												
                        
                        }
                        else { $price = 0; } ?>
                     <div class="price">from €{{$price}}</div>
                     <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Course Details</a>
                  </div>
                  <!-- ./item -->
               </div>
               @endif
               @endforeach
            </div>
            <!-- ./dynamic-courses-wrapper -->
            
         </div>
         @endif
         <!--
         @if(isset($instructors))
         <div class="row row-flex row-flex-23 instructors-results">
            @foreach($instructors as $lkey => $lvalue)

            <?php

               
               $search1 = strpos(trim(strtolower($lvalue['title'])),trim(strtolower($search_term)));
               $search2 = strpos(trim(strtolower($lvalue['subtitle'])),trim(strtolower($search_term)));
               $search3 = strpos(trim(strtolower($lvalue['header'])),trim(strtolower($search_term)));
               $search4 = false;


            ?>

            <div class="col-3 col-sm-6 col-xs-12">
               <div class="card instructor-box">
                  <div class="instructor-inner">
                     <div class="profile-img">
                        @if(isset($lvalue['featured'][0]['media']))
                        <a href="{{ $lvalue->slug }}" title="{{ $frontHelp->pField($lvalue, 'title') }}"><img src="{{ $frontHelp->pImg($lvalue, 'instructors-testimonials') }}" alt="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}"></a>
                        @endif
                     </div>
                     <h3><a href="{{ $lvalue->slug }}">{{ $lvalue['title'] }} {{ $lvalue['subtitle'] }}</a></h3>
                     @if(isset($lvalue['header']))
                     <p >{{ $lvalue['header'] }}, <a target="_blank" href="{{ $lvalue['ext_url'] }}">@if($lvalue['c_fields']['simple_text'][1]['value'] != ''){{ $lvalue['c_fields']['simple_text'][1]['value'] }} @endif</a>.</p>
                     @endif
                     <ul class="social-wrapper">
                        @if(isset($lvalue['c_fields']['simple_text'][2]) && $lvalue['c_fields']['simple_text'][2]['value'] != '')
                        <li><a target="_blank" href="{{ $lvalue['c_fields']['simple_text'][2]['value'] }}"><img src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                        @endif
                        @if(isset($lvalue['c_fields']['simple_text'][4]) && $lvalue['c_fields']['simple_text'][4]['value'] != '')
                        <li><a target="_blank" href="{{ $lvalue['c_fields']['simple_text'][4]['value'] }}"><img src="/theme/assets/images/icons/social/Instagram.svg" width="16" alt="Visit"></a></li>
                        @endif
                        @if(isset($lvalue['c_fields']['simple_text'][3]) && $lvalue['c_fields']['simple_text'][3]['value'] != '')
                        <li><a target="_blank" href="#"><img src="/theme/assets/images/icons/social/Pinterest.svg" width="16" alt="Visit"></a></li>
                        @endif
                        @if(isset($lvalue['c_fields']['simple_text'][3]) && $lvalue['c_fields']['simple_text'][3]['value'] != '')
                        <li><a target="_blank" href="{{ $lvalue['c_fields']['simple_text'][3]['value'] }}"><img src="/theme/assets/images/icons/social/Twitter.svg" width="16" alt="Visit"></a></li>
                        @endif
                     </ul>
                  </div>
                  <!-- /.instructor-inner -->
                  <!--
               </div>
               <!-- /.instructor-box -->
            <!--</div>
            @endforeach
            <!-- /.col-3.col-sm-12 -->
         <!--</div>
         @endif
         -->
         <!-- /.row -->
      </div>
      <!-- /.search-results-wrapper -->
   </div>
   @endif
   <!-- /.container -->
</main>
@endsection
@section('scripts')
@stop
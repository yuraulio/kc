@php
   $results = 0;
   if(!empty($list)){
         $results += count($list);
   }

   $list = $dynamic_page_data["event_search_data"]["list"] ?? null;
   $search_term = $dynamic_page_data["event_search_data"]["search_term"] ?? null;
   $search_term_slug = $dynamic_page_data["event_search_data"]["search_term_slug"] ?? null;
   $instructor = $dynamic_page_data["event_search_data"]["instructor"] ?? null;
   $sumStudentsCategories = $dynamic_page_data["event_search_data"]['sumStudentsByCategories'] ?? null;

@endphp

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
   @if (count($list) > 0)
      <div class="section section--dynamic-learning">
         @foreach($list as $key => $row)

         <?php $info = $row->event_info(); ?>

         @if(isset($info['delivery']) && $info['delivery'] != 143)
            <?php

                $chmonth = date('m', strtotime($row['published_at']));
                $month = date('F Y', strtotime($row['published_at']));


                $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                    return $cartItem->id === $row->id;
                });

                ?>
            <?php
            $location = [];
            $eventtype = [];
            $advancedtag = 0;
            $duration = '';
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
            if($row['city'] && count($row['city']) != 0){
               $loc = $row['city'][0]['name'];
            }


            $search1 = strpos(trim(strtolower($loc)),trim(strtolower($search_term)));
            $search2 = strpos(trim(strtolower($row['title'])),trim(strtolower($search_term)));
           // $search3 = strpos(trim(strtolower($lvalue['header'])),trim(strtolower($search_term)));
           // $search4 = false;
            //dd($row);

            ?>

         <!-- ./dynamic-learning--subtitle -->
            <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
               <div class="item">
                  <div class="left">
                     <h2 class="@if($search2!==false) search-highlight @endif">{{ $row['title'] }}</h2>

                     <div class="bottom">
                        @include('new_web.components.event_infos',['type' => 'inclass'])
                        </div>
                  </div>
                  <div class="right">
                   @php
                       if ($row['ticket'] && isset($row['ticket']->where('type','Early Bird')->first()->pivot->price) &&
                           $row['ticket']->where('type','Early Bird')->first()->pivot->price > 0 &&
                           $row['ticket']->where('type','Early Bird')->first()->pivot->quantity > 0 &&
                           $row['ticket']->where('type','Early Bird')->first()->pivot->active) {
                           $price = $row['ticket']->where('type','Early Bird')->first()->pivot->price;
                       }else if($row['ticket'] && isset($row['ticket']->where('type','Special')->first()->pivot->price) &&
                           $row['ticket']->where('type','Special')->first()->pivot->price > 0 &&
                           $row['ticket']->where('type','Special')->first()->pivot->quantity > 0 &&
                           $row['ticket']->where('type','Special')->first()->pivot->active){
                           $price = $row['ticket']->where('type','Special')->first()->pivot->price;
                       }else if($row['ticket'] && isset($row['ticket']->where('type','Regular')->first()->pivot->price) &&
                           $row['ticket']->where('type','Regular')->first()->pivot->price > 0 &&
                           $row['ticket']->where('type','Regular')->first()->pivot->quantity > 0 &&
                           $row['ticket']->where('type','Regular')->first()->pivot->active){
                           $price = $row['ticket']->where('type','Regular')->first()->pivot->price;
                       }
                       else {
                           $price = 0;
                       }
                   @endphp
                     @if($row['view_tpl'] == 'elearning_pending')
                        <div class="price">Pending</div>
                     @elseif((isset($info['payment_method']) && $info['payment_method'] == 'free'))
                        <div class="price">free</div>
                     @elseif($row['status'] == 0)
                        <div class="price">from €{{$price}}</div>
                     @endif
                     @if ($row['slugable'])
                       <a href="{{ $row['slugable']['slug'] }}" class="btn btn--secondary btn--md">Course Details</a>
                     @endif
                  </div>
               </div>
            </div>
            <!-- ./item -->
            @else
            <!-- ./dynamic-learning--subtitle -->
            <div class="dynamic-courses-wrapper">
               <div class="item">
                  <div class="left">
                     <h2>{{ $row['title'] }}</h2>
                     <div class="bottom">
                     @include('new_web.components.event_infos',['type' => 'elearning'])
                     </div>
                  </div>
                  <div class="right">

                     <?php
                    if(count($row['ticket']) > 1)
                    {
                        $price = $row['ticket'][1]['pivot']['price'];
                    }
                        else { $price = 0; } ?>
                   @if($row['view_tpl'] == 'elearning_pending')
                     <div class="price">Pending</div>
                  @elseif((isset($info['payment_method']) && $info['payment_method'] == 'free'))
                     <div class="price">free</div>
                  @elseif($row['status'] == 0)
                     <div class="price">from €{{$price}}</div>
                  @endif
                     <a href="{{ $row['slugable']['slug'] }}" class="btn btn--secondary btn--md">Course Details</a>
                  </div>
                  <!-- ./item -->
               </div>
               @endif
               @endforeach
            </div>
            <!-- ./dynamic-courses-wrapper -->


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

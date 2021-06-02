@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<main id="main-area" role="main">
   <div class="section section--dynamic-learning">
      <div class="container">
         <div class="dynamic-learning--title">
            <?php $elern = false; $diplomas = false; $certificates = false; ?>
            
            @if($cat_dets->parent_id == 12)
            <?php //if($cat_dets->slug === 'e-learning-courses'){$elern = true; } 
               if($cat_dets->slug === 'video-on-demand-courses'){$elern = true; } 
               if($cat_dets->slug === 'diplomas'){$diplomas = true; }
               if($cat_dets->slug === 'certificates'){$certificates = true; }
               ?>
            <h1 >{{ $cat_dets->name }}</h1>
            @endif

            @if($cat_dets->parent_id == 216)
            <?php //if($cat_dets->slug === 'e-learning-courses'){$elern = true; } 
               if($cat_dets->slug === 'certificates'){$certificates = true; }
               if($cat_dets->slug === 'diplomas'){$diplomas = true; }
               ?>
            <h1 >{{ $cat_dets->name }}</h1>
            @endif

            @if($cat_dets->parent_id == 9)
            <h1>Events in {{ $cat_dets->name }}</h1>
            @endif

            @if($cat_dets->parent_id == 22)
            <h1 >Events for {{ $cat_dets->name }}</h1>
            @endif
            {{-- @if(isset($openlist) && count($openlist) > 0)--}}
            <p>{{ $cat_dets->description }}</p>
            {{--      @endif--}}
         </div>
         <!-- ./dynamic-learning--title -->
         @if(!$elern)
         <div class="control-wrapper-filters">
            <div class="filters">
               <a href="#upcoming" class="active">upcoming</a>
               <a href="#past">past</a>
            </div>
         </div>
         @endif
         <!-- ./dynamic-learning--subtitle -->
         <?php
            $countcompl = 0;
            $countopen = 0;
            $countsold = 0;
            ?>
         <div class="filters-wrapper">
           
            <div id="upcoming" class="filter-tab active-tab">
            
               @if(isset($openlist) && count($openlist) > 0)
                  <?php
                     $countopen = count($openlist);
                     $lastmonth1 = '';
                  ?>

                  @foreach($openlist as $key => $row)
                     @if($key===0)
                        <div style="height:100px">
                        </div>
                     @endif
                     @if($row->view_tpl == 'elearning_english' || $row->view_tpl == 'elearning_greek' || $row->view_tpl == 'elearning_free' || $row->view_tpl == 'elearning_pending')
                     <div class="dynamic-courses-wrapper">
                        <div class="item">
                           <div class="left">
                              <h2><a href="{{ $frontHelp->pSlug($row) }}">{{ $frontHelp->pField($row, 'title') }}</a></h2>
                              <div class="bottom">
                                 @if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '')
                                 <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $row['c_fields']['simple_text'][0]['value'] }} </div>
                                 @endif
                                 @if($row['c_fields']['simple_text'][12] && (is_numeric(substr($row['c_fields']['simple_text'][12]['value'], 0, 1))))
                                 <div class="expire-date"><img width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt="">{{ $row['c_fields']['simple_text'][12]['value'] }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="right">
                              <?php  if (isset($eventprices[$row->id])) {
                                 $price = $eventprices[$row->id];												

                                 }
                                 else { $price = 0; } ?>
                              @if($row->view_tpl == 'elearning_free')
                              <div class="price">free</div>
                              <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Enroll For Free</a>
                              @elseif($row->view_tpl == 'elearning_pending')
                              <div class="price">Pending</div>
                              <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Course Details</a>
                              @else
                              <div class="price">from €{{$price}}</div>
                              <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Course Details</a>
                              @endif
                           </div>
                           <!-- ./item -->
                        </div>
                     </div>
                     @else
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

                        @if($chmonth != $lastmonth1) 
                           <?php $lastmonth1 = $chmonth;?>
                           <div class="dynamic-learning--subtitle">
                              <h2>{{$month}}</h2>
                           </div>
                        @endif

                        <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                           <div class="item">
                              <div class="left">
                                 <h2><a href="{{ $frontHelp->pSlug($row) }}">{{ $frontHelp->pField($row, 'title') }}</a></h2>
                                 <div class="bottom">
                                    @if(isset($location->name))   
                                       <a href="{{ $location->slug }}" class="location " title="{{ $location->name }}">
                                       <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $location->name }}</a>  
                                    @endif
                                    @if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '')
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $row['c_fields']['simple_text'][0]['value'] }} </div>
                                    @endif
                                    @if($row['c_fields']['simple_text'][12] && (is_numeric(substr($row['c_fields']['simple_text'][12]['value'], 0, 1)))) 
                                       <div class="expire-date"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt="">{{ $row['c_fields']['simple_text'][12]['value'] }}</div>
                                    @endif
                                 
                                 </div>
                              </div>  
                              <div class="right">
                                 <?php  if (isset($eventprices[$row->id])) {
                                    $price = $eventprices[$row->id];												
                                 
                                    }
                                    else { $price = 0; } 
                                 ?>
                                 <?php $etstatus = 0 ?>
                                 @if(isset($row['c_fields']['dropdown_select_status']['value']))
                                    <?php $etstatus = $row['c_fields']['dropdown_select_status']['value']; ?>
                                 @endif

                                 @if($row->view_tpl == 'event_free')
                                    <div class="price">free</div>
                                    <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Enroll For Free</a>

                                 @elseif($row->view_tpl == 'event_free_coupon')
                                    <div class="price">free</div>
                                    <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">course details</a>
                                 @else
                                    @if($etstatus == 0 && $price > 0) 
                                    <div class="price">from €{{$price}}</div>
                                    <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Course Details</a>
                                    @else
                                    <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md btn--sold-out">sold out</a>
                                    @endif
                                 @endif
                              </div>        
                           </div>
                        </div>

                     @endif
                  @endforeach

                 
               @else

                  
               <h2> No available courses for now </h2>
               @endif

            </div>

            <div id="past" class="filter-tab">
               @if(isset($completedlist) && count($completedlist) > 0)
                  <?php
                     $countopen = count($completedlist);
                     $lastmonth1 = '';
                  ?>

               @foreach($completedlist as $row)
                  @if($row->view_tpl != 'elearning_english' && $row->view_tpl != 'elearning_greek' && $row->view_tpl != 'elearning_free' && $row->view_tpl != 'elearning_pending')
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

                     @if($chmonth != $lastmonth1) 
                        <?php $lastmonth1 = $chmonth;?>
                        <div class="dynamic-learning--subtitle">
                           <h2>{{$month}}</h2>
                        </div>
                     @endif

                     <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                           <div class="item">
                              <div class="left">
                                 <h2><a href="{{ $frontHelp->pSlug($row) }}">{{ $frontHelp->pField($row, 'title') }}</a></h2>
                                 <div class="bottom">
                                    @if(isset($location->name))   
                                       <a href="{{ $location->slug }}" class="location " title="{{ $location->name }}">
                                       <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $location->name }}</a>  
                                    @endif
                                    @if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '')
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $row['c_fields']['simple_text'][0]['value'] }} </div>
                                    @endif
                                    @if($row['c_fields']['simple_text'][12] && (is_numeric(substr($row['c_fields']['simple_text'][12]['value'], 0, 1)))) 
                                       <div class="expire-date"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt="">{{ $row['c_fields']['simple_text'][12]['value'] }}</div>
                                    @endif
                                 
                                 </div>
                              </div>  
                              <div class="right">
                                 <?php  if (isset($eventprices[$row->id])) {
                                    $price = $eventprices[$row->id];												
                                 
                                    }
                                    else { $price = 0; } 
                                 ?>
                                 <?php $etstatus = 0 ?>
                                 <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md btn--completed">completed</a>
                              </div>        
                           </div>
                        </div>

                  @endif
               @endforeach

               @endif
            </div>
           
         </div>

      
           
    
      </div>

  

   </div>

   <!-- /#main-area -->
</main>
@endsection
@section('scripts')
@stop
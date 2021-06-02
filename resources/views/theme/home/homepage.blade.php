@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<?php
   if(Session::has('scopeone')){
       $fone = Session::get('scopeone');
   }
   else { $fone = 0; }
   
   $totalfound = 0;
   $totalcats = 0;
   ?>
<main id="main-area" class="with-hero" role="main">
   <?php if (isset($homeBanners) && count($homeBanners) > 0) : ?>
   <?php foreach ($homeBanners as $key => $value) : ?>
   <?php if(isset($value['image'])) : ?>
   <section class="section-hero"  style="background-image:url('{{ $value['image'] }}');">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message">
            <h1>{{ $value['title'] }}</h1>
            <h2>{{ $value['subtitle'] }}</h2>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
   <?php 
      endif;
      endforeach;
      endif; ?>
   @if(isset($events))
   @foreach($eventsbycategory as $bcatid => $bcateventids)
   @if(isset($eventsbycategoryHelper) && isset($eventsbycategoryDetailsHelper) && isset($eventsbycategoryHelper[$bcatid]) && 
   ($eventsbycategoryDetailsHelper[$bcatid]->view_tpl !== 'elearning_english' && $eventsbycategoryDetailsHelper[$bcatid]->view_tpl !== 'elearning_pending' && $eventsbycategoryDetailsHelper[$bcatid]->view_tpl !== 'elearning_greek' 
         && $eventsbycategoryDetailsHelper[$bcatid]->view_tpl !== 'elearning_free' && $eventsbycategoryDetailsHelper[$bcatid]->view_tpl !== 'event_free' && $eventsbycategoryDetailsHelper[$bcatid]->view_tpl !== 'event_free_coupon'))
   <section class="section-text-carousel background event-background">
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
                         
                         $until = $eventsbycategoryHelper[$bcatid]->name
                         ?>
                  <h2>{{ $until }}</h2>
                  <?php
                     $location = [];
                     $eventtype = [];
                     $eventtopic = [];
                     $advancedtag = 0;
                     $advancedtagslug = '';
                     if (isset($eventsbycategoryDetailsHelper[$bcatid]->categories) && !empty($eventsbycategoryDetailsHelper[$bcatid]->categories)) :
                         foreach ($eventsbycategoryDetailsHelper[$bcatid]->categories as $category) :
                             /*if ($category->depth != 0 && $category->parent_id == 9) {
                                  $location=$category;
                             }
                             if ($category->depth != 0 && $category->parent_id == 12) {
                                  $eventtype=$category;
                             }
                             if ($category->depth != 0 && $category->parent_id == 22) {
                                 $eventtopic[]=$category->id;
                             }*/
                     
                             if ($category->id == 117) {
                                 $advancedtag = 1;
                                 $advancedtagslug = $category->slug;
                             }
                         endforeach;
                     endif;
                     ?>
                  @if (isset($eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]) && $eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]['value'] != '')
                  @if(isset($eventsbycategoryHelper[$bcatid]) && $eventsbycategoryHelper[$bcatid]->hours)<span class="duration"><img src="{{ cdn('/theme/assets/images/icons/Start-Finish.svg')}}" class="replace-with-svg" alt=""/>{{ $eventsbycategoryHelper[$bcatid]->hours }}</span>@endif
                  @endif
                  <p>{!!$eventsbycategoryHelper[$bcatid]->description!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     @foreach($events as $key => $row)
                     @if($row->view_tpl != 'event_free' && $row->view_tpl != 'event_free_coupon')
                     <?php
                        $location = [];
                        $eventtype = [];
                        $eventtopic = [];
                        $advancedtag = 0;
                        $onthiscat = 0;
                        $advancedtagslug = '';
                        $categoryEvent = false;

                        if (isset($row->categories) && !empty($row->categories)) :
                        foreach ($row->categories as $category) :
                        if ($category->depth != 0 && $category->parent_id == 9) {
                            $location=$category;
                        }

                        if ($category->parent_id == 12) {
                           $categoryType=$category;
                       }

                        if ($category->depth != 0 && $category->parent_id == 12) {
                            $eventtype=$category;
                        }
                                   if ($category->depth != 0 && $category->parent_id == 22) {
                                       $eventtopic[]=$category->id;
                                   }
                                   if ($category->depth != 0 && $category->parent_id == 45) {
                                       $onthiscat=$category->id;
                                   }
                                   if ($category->id == 117) {
                                       $advancedtag = 1;
                                       $advancedtagslug = $category->slug;
                                   }
                        endforeach;
                        endif;
                        
                        $dont = true;
                        
                        
                        
                           $totalfound++;
                           $chmonth = date('m', strtotime($row->published_at));
                        $month = date('F Y', strtotime($row->published_at));
                        if($chmonth != $lastmonth) {
                        $lastmonth = $chmonth;
                        }
                           ?>
                     @if($onthiscat == $bcatid)
                     <div class="slide">
                     <?php
                              $string = $frontHelp->pField($row, 'title');
                               if( strpos($string, ',') !== false ) {
                                 $until = substr($string, 0, strrpos($string, ","));
                               }
                               else {
                                  $until = $string;
                                  } ?>
                        @if (!empty($row['featured']) && isset($row['featured'][0]) &&isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                        <a href="{{ $frontHelp->pSlug($row) }}"><img src="{{ cdn($frontHelp->pImg($row, 'event-card')) }}" alt="{{$until}}"/></a>
                        @endif
                        <div class="box-text">
                           <?php
                              $string = $frontHelp->pField($row, 'title');
                               if( strpos($string, ',') !== false ) {
                                 $until = substr($string, 0, strrpos($string, ","));
                               }
                               else {
                                  $until = $string;
                                  } ?>
                     
                           <?php
                              if (isset($eventprices[$row->id])) {
                                      $price = $eventprices[$row->id];
                              }
                              else { $price = 0; } ?>
                           @if(isset($row['c_fields']['dropdown_select_status']['value']))
                           <?php $etstatus = $row['c_fields']['dropdown_select_status']['value']; ?>
                           @endif
                           <h3><a href="{{ $frontHelp->pSlug($row) }}">{{ $until }}</a></h3>
                           @if(isset($location->name)) 
                           
                              <a href="{{ $location->slug }}" class="location">{{ $location->name }}</a> 
                           
                           @else
                              <a href="{{$categoryType['slug']}}" class="location">{{ $categoryType['name'] }}</a> 
                           @endif
                           <span class="date"> {{$month}}</span>
                           @if(isset($row['c_fields']['dropdown_select_status']['value']))
                           <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];

                              if($price == 0 && $estatus == 0){
                                 $estatus = 2;
                              }

                              switch ($estatus) {
                              	case 0:
                              		//'OPEN'
                              		?>
                           <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary">course details</a>
                           <?php
                              break;
                              case 1:
                              	//'CLOSED'
                              ?>                            <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary btn--completed">closed</a>
                           <?php
                              break;
                              case 2:
                              	//'SOLD-OUT'
                              ?>                            <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                           <?php
                              break;
                              case 3:
                              	//'COMPLETED'
                              ?>                            <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary btn--sold-out">course details</a>
                           <?php
                              break;
                              default:
                              ?>
                           <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary btn--completed">course details</a>
                           <?php
                              break;
                              }
                              ?>
                           @else
                           <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary">course details</a>
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
   </section>
   @endif
   @endforeach
   @endif

   @if(isset($events))
   @foreach($eventsbycategory as $bcatid => $bcateventids)
   @if(isset($eventsbycategoryHelper) && isset($eventsbycategoryDetailsHelper) && isset($eventsbycategoryHelper[$bcatid]) && 
   ($eventsbycategoryDetailsHelper[$bcatid]->view_tpl === 'elearning_english' || $eventsbycategoryDetailsHelper[$bcatid]->view_tpl === 'elearning_pending' || $eventsbycategoryDetailsHelper[$bcatid]->view_tpl === 'elearning_greek' || $eventsbycategoryDetailsHelper[$bcatid]->view_tpl === 'elearning_free'))
   <section class="section-text-carousel section--blue-gradient">
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
                         $until = $eventsbycategoryHelper[$bcatid]->name
                         ?>
                  <?php
                     $location = [];
                     $eventtype = [];
                     $eventtopic = [];
                     $advancedtag = 0;
                     $advancedtagslug = '';
                     $categoryType=false;
                     if (isset($eventsbycategoryDetailsHelper[$bcatid]->categories) && !empty($eventsbycategoryDetailsHelper[$bcatid]->categories)) :
                         foreach ($eventsbycategoryDetailsHelper[$bcatid]->categories as $category) :
                             /*if ($category->depth != 0 && $category->parent_id == 9) {
                                  $location=$category;
                             }
                             if ($category->depth != 0 && $category->parent_id == 12) {
                                  $eventtype=$category;
                             }
                             if ($category->depth != 0 && $category->parent_id == 22) {
                                 $eventtopic[]=$category->id;
                             }*/
                     
                              if ($category->parent_id == 12) {
                                 $categoryType=$category;
                              }   

                             if ($category->id == 117) {
                                 $advancedtag = 1;
                                 $advancedtagslug = $category->slug;
                             }
                         endforeach;
                     endif;
                     ?>
                  <h2>{{$until}}</h2>
                  @if (isset($eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]) && $eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]['value'] != '')
                  @if(isset($eventsbycategoryHelper[$bcatid]) && $eventsbycategoryHelper[$bcatid]->hours)<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{!!$eventsbycategoryHelper[$bcatid]->hours!!}</span>@endif
                  @endif
                  <p>{!!$eventsbycategoryHelper[$bcatid]->description!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     @foreach($events as $key => $row)
                     @if($row->view_tpl != 'elearning_free' && $row->view_tpl != 'event_free' && $row->view_tpl != 'event_free_coupon')
                     <?php
                        $location = [];
                        $eventtype = [];
                           $eventtopic = [];
                           $advancedtag = 0;
                           $onthiscat = 0;
                           $advancedtagslug = '';
                        if (isset($row->categories) && !empty($row->categories)) :
                        foreach ($row->categories as $category) :
                        if ($category->depth != 0 && $category->parent_id == 9) {
                            $location=$category;
                        }

                        if ($category->depth != 0 && $category->parent_id == 12) {
                            $eventtype=$category;
                        }
                                   if ($category->depth != 0 && $category->parent_id == 22) {
                                       $eventtopic[]=$category->id;
                                   }
                                   if ($category->depth != 0 && $category->parent_id == 45) {
                                       $onthiscat=$category->id;
                                   }
                                   if ($category->id == 117) {
                                       $advancedtag = 1;
                                       $advancedtagslug = $category->slug;
                                   }
                        endforeach;
                        endif;
                        
                        $dont = true;
                        
                        
                        
                           $totalfound++;
                           $chmonth = date('m', strtotime($row->published_at));
                        $month = date('F Y', strtotime($row->published_at));
                        if($chmonth != $lastmonth) {
                        $lastmonth = $chmonth;
                        }
                           ?>
                     @if($onthiscat == $bcatid)
                     <div class="slide">
                     <?php
                           $string = $frontHelp->pField($row, 'title');
                            if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                               $until = $string;
                               } ?>
                        @if (!empty($row['featured']) && isset($row['featured'][0]) &&isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                        <a href="{{ $frontHelp->pSlug($row) }}"><img src="{{ cdn($frontHelp->pImg($row, 'event-card')) }}" alt="{{ $until}}"/></a>
                        @endif
                       
                        <div class="box-text">
                           <h3><a href="{{ $frontHelp->pSlug($row) }}">{{ $until}}</a></h3>
                          
                           <a href="{{$categoryType['slug']}}" class="location">{{ $categoryType['name'] }}</a> 
                           <span class="date"> </span>
                           <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary">course details</a>
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

   @if(isset($events))
   @foreach($inclassEventsbycategoryFree as $bcatid => $bcateventids)
   @if(isset($inclassEventsbycategoryHelperFree) && isset($inclassEventsbycategoryDetailsHelperFree) && isset($inclassEventsbycategoryHelperFree[$bcatid]) && 
   ($inclassEventsbycategoryDetailsHelperFree[$bcatid]->view_tpl === 'event_free' || $inclassEventsbycategoryDetailsHelperFree[$bcatid]->view_tpl === 'event_free_coupon'))
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

                         $until = $inclassEventsbycategoryHelperFree[$bcatid]->name
                         ?>
                  <?php
                     $location = [];
                     $eventtype = [];
                     $eventtopic = [];
                     $advancedtag = 0;
                     $advancedtagslug = '';
                     $categoryType= false;
                     if (isset($inclassEventsbycategoryDetailsHelperFree[$bcatid]->categories) && !empty($inclassEventsbycategoryDetailsHelperFree[$bcatid]->categories)) :
                         foreach ($inclassEventsbycategoryDetailsHelperFree[$bcatid]->categories as $category) :
                             /*if ($category->depth != 0 && $category->parent_id == 9) {
                                  $location=$category;
                             }
                             if ($category->depth != 0 && $category->parent_id == 12) {
                                  $eventtype=$category;
                             }
                             if ($category->depth != 0 && $category->parent_id == 22) {
                                 $eventtopic[]=$category->id;
                             }*/
                     
                              if ($category->parent_id == 12) {
                                 $categoryType=$category;
                              }  

                             if ($category->id == 117) {
                                 $advancedtag = 1;
                                 $advancedtagslug = $category->slug;
                             }
                         endforeach;
                     endif;
                     ?>
                  <h2>{{$until}}</h2>
                  @if (isset($inclassEventsbycategoryDetailsHelperFree[$bcatid]['c_fields']['simple_text'][12]) && $inclassEventsbycategoryDetailsHelperFree[$bcatid]['c_fields']['simple_text'][12]['value'] != '')
                  @if(isset($inclassEventsbycategoryHelperFree[$bcatid]) && $inclassEventsbycategoryHelperFree[$bcatid]->hours)<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{{ $inclassEventsbycategoryHelperFree[$bcatid]->hours }}</span>@endif
                  @endif
                  <p>{!!$inclassEventsbycategoryHelperFree[$bcatid]->description!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     @foreach($events as $key => $row)
                     @if($row->view_tpl == 'event_free' || $row->view_tpl == 'event_free_coupon')
                     <?php
                        $location = [];
                        $eventtype = [];
                        $eventtopic = [];
                        $advancedtag = 0;
                        $onthiscat = 0;
                        $advancedtagslug = '';
                        $hours ='';
                        if (isset($row->categories) && !empty($row->categories)) :
                        foreach ($row->categories as $category) :
                        if ($category->depth != 0 && $category->parent_id == 9) {
                            $location=$category;
                        }
                        if ($category->depth != 0 && $category->parent_id == 12) {
                            $eventtype=$category;
                        }
                                   if ($category->depth != 0 && $category->parent_id == 22) {
                                       $eventtopic[]=$category->id;
                                   }
                                   if ($category->depth != 0 && $category->parent_id == 45) {
                                       $onthiscat=$category->id;
                                   }
                                   if ($category->id == 117) {
                                       $advancedtag = 1;
                                       $advancedtagslug = $category->slug;
                                    
                                   }
                        endforeach;
                        endif;
                        
                        $dont = true;
                        
                        
                        
                           $totalfound++;
                           $chmonth = date('m', strtotime($row->published_at));
                        $month = date('F Y', strtotime($row->published_at));
                        if($chmonth != $lastmonth) {
                        $lastmonth = $chmonth;
                        }
                           ?>
                     @if($onthiscat == $bcatid)
                     <div class="slide">
                     <?php
                           $string = $frontHelp->pField($row, 'title');
                            if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                               $until = $string;
                               } ?>
                        @if (!empty($row['featured']) && isset($row['featured'][0]) &&isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                        <a href="{{ $frontHelp->pSlug($row) }}"><img src="{{ cdn($frontHelp->pImg($row, 'event-card')) }}" alt="{{ $until}}"/></a>
                        @endif
                       
                        <div class="box-text">
                           <h3><a href="{{ $frontHelp->pSlug($row) }}">{{ $until}}</a></h3>
             
                           <a href="{{$categoryType['slug']}}" class="location">{{ $categoryType['name'] }}</a> 
                           <span class="date"></span>
                           @if($row->view_tpl == 'event_free')
                           <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary">enroll for free</a>
                           @elseif($row->view_tpl == 'event_free_coupon')
                           <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary">course details</a>                           
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
   @endif
  
   @endforeach
   @endif

   @if(isset($events))
   @foreach($eventsbycategoryFree as $bcatid => $bcateventids)
   @if(isset($eventsbycategoryHelperFree) && isset($eventsbycategoryDetailsHelperFree) && isset($eventsbycategoryHelperFree[$bcatid]) && 
   ($eventsbycategoryDetailsHelperFree[$bcatid]->view_tpl === 'elearning_free'))
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

                         $until = $eventsbycategoryHelperFree[$bcatid]->name
                         ?>
                  <?php
                     $location = [];
                     $eventtype = [];
                     $eventtopic = [];
                     $advancedtag = 0;
                     $advancedtagslug = '';
                     $categoryType= false;
                     if (isset($eventsbycategoryDetailsHelperFree[$bcatid]->categories) && !empty($eventsbycategoryDetailsHelperFree[$bcatid]->categories)) :
                         foreach ($eventsbycategoryDetailsHelperFree[$bcatid]->categories as $category) :
                             /*if ($category->depth != 0 && $category->parent_id == 9) {
                                  $location=$category;
                             }
                             if ($category->depth != 0 && $category->parent_id == 12) {
                                  $eventtype=$category;
                             }
                             if ($category->depth != 0 && $category->parent_id == 22) {
                                 $eventtopic[]=$category->id;
                             }*/
                     
                              if ($category->parent_id == 12) {
                                 $categoryType=$category;
                              }  

                             if ($category->id == 117) {
                                 $advancedtag = 1;
                                 $advancedtagslug = $category->slug;
                             }
                         endforeach;
                     endif;
                     ?>
                  <h2>{{$until}}</h2>
                  @if (isset($eventsbycategoryDetailsHelperFree[$bcatid]['c_fields']['simple_text'][12]) && $eventsbycategoryDetailsHelperFree[$bcatid]['c_fields']['simple_text'][12]['value'] != '')
                  @if(isset($eventsbycategoryHelperFree[$bcatid]) && $eventsbycategoryHelperFree[$bcatid]->hours)<span class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""/>{{ $eventsbycategoryHelperFree[$bcatid]->hours }}</span>@endif
                  @endif
                  <p>{!!$eventsbycategoryHelperFree[$bcatid]->description!!}</p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     @foreach($events as $key => $row)
                     @if($row->view_tpl == 'elearning_free')
                     <?php
                        $location = [];
                        $eventtype = [];
                           $eventtopic = [];
                           $advancedtag = 0;
                           $onthiscat = 0;
                           $advancedtagslug = '';
                           $hours ='';
                        if (isset($row->categories) && !empty($row->categories)) :
                        foreach ($row->categories as $category) :
                        if ($category->depth != 0 && $category->parent_id == 9) {
                            $location=$category;
                        }
                        if ($category->depth != 0 && $category->parent_id == 12) {
                            $eventtype=$category;
                        }
                                   if ($category->depth != 0 && $category->parent_id == 22) {
                                       $eventtopic[]=$category->id;
                                   }
                                   if ($category->depth != 0 && $category->parent_id == 45) {
                                       $onthiscat=$category->id;
                                   }
                                   if ($category->id == 117) {
                                       $advancedtag = 1;
                                       $advancedtagslug = $category->slug;
                                    
                                   }
                        endforeach;
                        endif;
                        
                        $dont = true;
                        
                        
                        
                           $totalfound++;
                           $chmonth = date('m', strtotime($row->published_at));
                        $month = date('F Y', strtotime($row->published_at));
                        if($chmonth != $lastmonth) {
                        $lastmonth = $chmonth;
                        }
                           ?>
                     @if($onthiscat == $bcatid)
                     <div class="slide">
                     <?php
                           $string = $frontHelp->pField($row, 'title');
                            if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                               $until = $string;
                               } ?>
                        @if (!empty($row['featured']) && isset($row['featured'][0]) &&isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                        <a href="{{ $frontHelp->pSlug($row) }}"><img src="{{ cdn($frontHelp->pImg($row, 'event-card')) }}" alt="{{ $until}}"/></a>
                        @endif
                       
                        <div class="box-text">
                           <h3><a href="{{ $frontHelp->pSlug($row) }}">{{ $until}}</a></h3>
             
                           <a href="{{$categoryType['slug']}}" class="location">{{ $categoryType['name'] }}</a> 
                           <span class="date"></span>
                           <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--sm btn--secondary">enroll for free</a>
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
         @if(isset($homeBrands) && count($homeBrands) > 0)
         @if(isset($block_brands) && isset($block_brands[0]))
         <h2 class="section-title">{{ $block_brands[0]->title }}</h2>
         @endif
         <div class="row row-flex row-flex-0">
            <?php
               $rand_keys = array_rand($homeBrands, 6);
               //   echo print_r($rand_keys);
               //  echo print_r($homeBrands[7]['image']);
               
               ?>
            @foreach ($rand_keys as $key)
            <div class="col">
               <div class="partners-set">
                  <img alt="{{ $homeBrands[$key]['title'] }}" title="{{ $homeBrands[$key]['title'] }}" src="{{ cdn($homeBrands[$key]['image']) }}" align=""/>
               </div>
            </div>
            @endforeach
         </div>
         <div class="view-more">
            <a href="/they-trust-us">See more</a>
         </div>
         @endif
         <hr>
         @if(isset($block_logos) && isset($block_logos[0]))
         <h2 class="section-title">{{ $block_logos[0]->title }}</h2>
         <div class="row row-flex row-flex-0">
            <?php
               $rand_keys = array_rand($homeLogos, 6);
               //   echo print_r($rand_keys);
               //  echo print_r($homeBrands[7]['image']);
               
               ?>
            @foreach ($rand_keys as $key)
            <div class="col">
               <div class="partners-set">
                  <img alt="{{ $homeLogos[$key]['title'] }}" title="{{ $homeLogos[$key]['title'] }}" src="{{ cdn($homeLogos[$key]['image']) }}" align=""/>
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
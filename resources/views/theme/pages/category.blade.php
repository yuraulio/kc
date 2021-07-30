@extends('theme.layouts.master')
@section('content')
<main id="main-area" role="main">
   <div class="section section--dynamic-learning">
      <div class="container">
         <div class="dynamic-learning--title">
            <?php $elern = false; $diplomas = false; $certificates = false; ?>
            <?php //dd(count($type)); ?>
            @if(isset($delivery))
            <?php
               if( $delivery['slugable']['slug'] === 'video-on-demand-courses'){$elern = true; }
               ?>
            <h1 >
            {{ $delivery->name }}
            </h1>
            <p>{{ $delivery->description }}</p>
            @elseif(isset($city))
            <h1 >{{ $city['name'] }}</h1>
            <p>{{ $city['description'] }}</p>
            @else
            <?php
               if( $type['slugable']['slug'] === 'diplomas'){$diplomas = true; }
               if( $type['slugable']['slug'] === 'certificates'){$certificates = true; }
               ?>
            <h1 >

                {{ $type->name }}

            </h1>
            <p>{{ $type->description }}</p>
            @endif

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
                     <?php //dd($row['expiration']); ?>
                     @if($row->view_tpl == 'elearning_event' || $row->view_tpl == 'elearning_greek' || $row->view_tpl == 'elearning_free' || $row->view_tpl == 'elearning_pending')
                     <div class="dynamic-courses-wrapper">
                        <div class="item">
                           <div class="left">
                                <?php
                                    if(isset($row['slugable']['slug'])){
                                        $slug = $row['slugable']['slug'];
                                    }else{
                                        $slug = '';
                                    }
                                ?>
                              <h2><a href="{{ $slug }}">{{ $row->title}}</a></h2>
                              <div class="bottom">
                                 @if (isset($row['expiration']) && $row['expiration'] != null)
                                 <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $row['expiration'] }} months access to videos & files  </div>
                                 @endif
                                 @if(isset($row['hours']))
                                 <div class="expire-date"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt="">{{ $row['hours'] }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="right">
                              <?php  if (isset($row['ticket'][0]['pivot']['price'])) {
                                 $price = $row['ticket'][0]['pivot']['price'];

                                 }
                                 else { $price = 0; } ?>
                              @if($row->view_tpl == 'elearning_free')
                              <div class="price">free</div>
                              <a href="{{ $slug }}" class="btn btn--secondary btn--md">Enroll For Free</a>
                              @elseif($row->view_tpl == 'elearning_pending')
                              <div class="price">Pending</div>
                              <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
                              @else
                              <div class="price">from €{{$price}}</div>
                              <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
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

                        @if($chmonth != $lastmonth1)
                           <?php $lastmonth1 = $chmonth;?>
                           <div class="dynamic-learning--subtitle">
                              <h2>{{$month}}</h2>
                           </div>
                        @endif
                        <div class="dynamic-courses-wrapper <?= (!$elern) ? 'dynamic-courses-wrapper--style2' : ''; ?>">
                           <div class="item">
                              <div class="left">
                                  <?php
                                  if(isset($row['slugable']['slug'])){
                                      $slug = $row['slugable']['slug'];
                                  }else{
                                    $slug = '';
                                  }
                                  ?>
                                 <h2><a href="{{ $slug }}">{{ $row->title}}</a></h2>
                                 <div class="bottom">
                                    @if(isset($row['city']))
                                        @foreach($row['city'] as $city)
                                            <a href="{{ $city->slugable->slug }}" class="city " title="{{ $city->name }}">
                                            <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $city->name }}</a>
                                        @endforeach
                                    @endif

                                    @if (isset($row['expiration']) && $row['expiration'] != null)
                                        <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $row['expiration'] }} months access to videos & files  </div>
                                    @endif

                                    @if(isset($row['summary1']) )
            
                                        @foreach($row['summary1'] as $sum)
                                            @if($sum['section'] == 'duration')
                                            <div class="expire-date"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt="">{{ $sum['title'] }}</div>
                                            @endif
                                        @endforeach
                                    @endif



                                 </div>
                              </div>
                              <?php //dd($row['ticket'][0]['pivot']['price']); ?>
                              <div class="right">
                                 <?php  if (isset($row['ticket'][0]['pivot']['price'])) {
                                    $price = $row['ticket'][0]['pivot']['price'];

                                    }
                                    else { $price = 0; }
                                 ?>
                                 <?php $etstatus = 0 ?>
                                 <?php //dd($row['status']); ?>
                                 @if(isset($row['status']))
                                    <?php $etstatus = $row['status']; ?>
                                 @endif



                                 @if($row->view_tpl == 'event_free')
                                    <div class="price">free</div>
                                    <a href="{{ $slug }}" class="btn btn--secondary btn--md">Enroll For Free</a>

                                 @elseif($row->view_tpl == 'event_free_coupon')
                                    <div class="price">free</div>
                                    <a href="{{ $slug }}" class="btn btn--secondary btn--md">course details</a>
                                 @else
                                    @if($etstatus == 0 && $price > 0)
                                    <div class="price">from €{{$price}}</div>
                                    <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
                                    @else
                                    <a href="{{ $slug }}" class="btn btn--secondary btn--md btn--sold-out">sold out</a>
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
                  @if($row->view_tpl != 'elearning_event' && $row->view_tpl != 'elearning_greek' && $row->view_tpl != 'elearning_free' && $row->view_tpl != 'elearning_pending')
                     <?php
                        $chmonth = date('m', strtotime($row->published_at));
                        $month = date('F Y', strtotime($row->published_at));

                        $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                           return $cartItem->id === $row->id;
                        });

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
                                <?php
                                    if(isset($row['slugable']['slug'])){
                                        $slug = $row['slugable']['slug'];
                                    }else{
                                        $slug = '';
                                    }
                                ?>
                                 <h2><a href="{{ $slug }}">{{ $row->title}}</a></h2>
                                 <div class="bottom">
                                    @if(isset($row['city']))
                                        @foreach($row['city'] as $city)

                                            <a href="{{$city->slugable->slug}}" class="city " title="{{ $city->name }}">
                                            <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $city->name }}</a>
                                        @endforeach
                                    @endif

                                    @if(isset($row['description']) && $row['description'] != '')
                                        <div  class="duration"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $row['description'] }} </div>
                                    @endif

                                    {{--@if(isset($location->name))
                                       <a href="{{ $location->slug }}" class="location " title="{{ $location->name }}">
                                       <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $location->name }}</a>
                                    @endif
                                    @if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '')
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $row['c_fields']['simple_text'][0]['value'] }} </div>
                                    @endif--}}



                                    @if(isset($row['summary1']))


                                        @foreach($row['summary1'] as $sum)

                                            @if($sum['section'] == 'date')
                                            <?php //dd($date); ?>
                                            <div class="expire-date"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt="">{{ $sum['title'] }}</div>
                                            @endif
                                        @endforeach
                                    @endif

                                 </div>
                              </div>
                              <div class="right">
                                 <?php  if (isset($row['ticket'][0]['pivot']['price'])) {
                                    $price = $row['ticket'][0]['pivot']['price'];

                                    }
                                    else { $price = 0; }
                                 ?>
                                 <?php $etstatus = 0 ?>
                                 <a href="{{ $slug }}" class="btn btn--secondary btn--md btn--completed">completed</a>
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

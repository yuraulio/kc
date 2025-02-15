<!-- SEATS -->
{{--@if(isset($section_seats) && $is_event_paid == 0)--}}

<?php
   $title = '';
   $body = '';
   if(isset($sections['tickets'])){
      $title = $sections['tickets']->first()->title;
      $body = $sections['tickets']->first()->description;
   }
?>

<section id="seats" class="section-tickets">
   
   <div class="container">
   
      <h2 class="section-title">{{$title}}</h2>

      <div class="row row-flex row-flex-15">
         
            <?php 
               $early = false;
               //$showAlumni = true;
            ?>
         @foreach($tickets as $key => $ticket)
         <?php 
            
            $options = json_decode($ticket['pivot']['options'],true); 
            
          
            if($ticket['type'] == 'Early Bird' && $ticket['pivot']['quantity'] > 0){
               
               $early = true;
            }else if($ticket['type'] == 'Early Bird'){
               continue;
            }

            if($ticket['type'] == 'Special' && $early){
               continue;
            } 

            if($ticket['type'] == 'Sponsored'){
               continue;
            } 

            if($ticket['type'] == 'Alumni' && (!Auth::user() || (Auth::user() && !Auth::user()->kc_id))){  
               $showAlumni = false;
               continue;
            }
            
            

         ?>
         <div class="@if($showAlumni && $showSpecial) col-4 col-sm-12 @elseif($showAlumni && !$showSpecial && count($tickets) > 1) col-6 col-sm-12 
                     @elseif(!$showAlumni && $ticket['type'] !== 'Alumni' && !$showSpecial)  
                     @elseif(count($tickets) > 1) col-6 col-sm-12 @endif 
                        
                     @if(!$showAlumni && ($ticket['type'] == 'Early Bird' || $ticket['type'] == 'Special')) ticket-right 
                     @elseif($ticket['type'] == 'Alumni' && !$showSpecial) ticket-right 
                     @elseif(!$showAlumni && $ticket['type'] !== 'Alumni' && !$showSpecial) ticket-center  
                     @elseif(count($tickets) == 1))
                     ticket-center  
                     @endif">
       
            <div class="ticket-box-wrapper">
               <div class="ticket-box">
                  <h3 class="@if($ticket['type'] != 'Alumni') special-ticket @endif">{{ $ticket['type'] }} <span> €{{$ticket['pivot']['price']}} </span></h3>
                  <div class="ticket-box-content">
                     <ul class="seat-features">

                        @foreach((array) json_decode($ticket['pivot']['features']) as $feature)
                           <li>{{ $feature }}</li>
                        @endforeach

                     </ul>
                  </div>
                  
                  <div class="ticket-box-price">
                     <span class="ticket-price hidden-xs">€{{$ticket['pivot']['price']}}</span>
                     <span class="ticket-infos"> {{ $ticket['subtitle'] }}</span>
                    
                     @if($ticket['pivot']['seats_visible'])<span class="ticket-infos hidden-xs">@if($ticket['type'] != 'Alumni' && ($event->view_tpl!='elearning_event')) {{ $ticket['pivot']['quantity'] }} seats remaining @else &nbsp; @endif</span> @endif
                  </div>
                  <div class="ticket-box-actions">
                     @if($ticket['type'] == 'Regular' && $early)
                        @if($ticket['pivot']['quantity'] <= 0)
                           <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                        @else
                           <div class="btn btn--lg btn--secondary btn--completed" >AVAILABLE SOON</div>
                        @endif
                     @else
                        @if(isset($options['dropdown']) && $options['dropdown'])
                           @if($ticket['pivot']['quantity'] <= 0)
                              <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                           @else

                              <div class="ticket-actions-wrapper">
                                 <a href="#" class="btn btn--lg btn--primary btn-ticket--dropdown">ENROLL NOW</a>
                                 <ul class="tickets-dropdown">
                                    @if($ticket['type'] != 'Alumni') 
                                    <li><a href="{{ route('cart.add-item', [ $event->id, $ticket['id'], 1 ]) }}" class="btn btn-add btn--lg btn--primary">UNEMPLOYED</a></li>
                                    <li><a href="{{ route('cart.add-item', [ $event->id, $ticket['id'], 2 ]) }}" class="btn btn-add btn--lg btn--primary">STUDENT</a></li>
                                    <li><a href="{{ route('cart.add-item', [ $event->id, $ticket['id'], 5 ]) }}" class="btn btn-add btn--lg btn--primary">GROUP</a></li>
                                    @endif
                                 </ul>
                              </div>
                           @endif
                        @else
                           @if($ticket['pivot']['quantity'] <= 0)
                              <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                           @else
                              @if($ticket['pivot']['price'] == 0)
                                 <div class="ticket-actions-wrapper">
                                    <a href="#" class="btn btn--lg btn--primary btn-ticket--dropdown">ENROLL NOW</a>
                                    <ul class="tickets-dropdown">
                                       <li><a class="btn btn-add btn--lg btn--primary" title="ENROLL NOW" href="{{ route('cart.add-item', [ $event->id, $ticket['id'], 3 ]) }}">KNOWCRUNCH ALUMNI</a></li>
                                       <li><a class="btn btn-add btn--lg btn--primary" title="ENROLL NOW" href="{{ route('cart.add-item', [ $event->id, $ticket['id'], 0 ]) }}">OTHER</a></li>
                                    </ul>
                                 </div>
                                 @else
                                    @if($ticket['type'] == 'Alumni')
                                       <div class="ticket-actions-wrapper">
                                          <a href="{{ route('cart.add-item', [ $event->id, $ticket['id'], 3 ]) }}" class="btn btn-add btn--lg btn--primary ">ENROLL NOW</a>
                                       </div>                                      
                                    @else
                                       <div class="ticket-actions-wrapper">
                                          <a class="btn btn-add btn--lg btn--primary"  title="ENROLL NOW" href="{{ route('cart.add-item', [ $event->id, $ticket['id'], 0 ]) }}">ENROLL NOW</a>
                                       </div>
                                    @endif

                              @endif
                           @endif
                        @endif
                     @endif
                  </div>
                
               </div>
            </div>
         </div>

                    
            
         @endforeach   
        
      </div>

   </div>
</section>
{{--@endif--}}
<!-- SEATS END -->
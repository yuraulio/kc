<!-- SEATS -->
@if(isset($section_seats) && $is_event_paid == 0)

<?php
                  $title = $section_seats->title ;
                  $body = $section_seats->body ;
                  $cont = $content->titles()->where('category','tickets');
                  
                  if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                        $title = $cont->first()->title;
                        $body = $cont->first()->body;
                     }
                  
                  ?>

<section id="seats" class="section-tickets">
   <?php $all = count($linkedseats); ?>
   @foreach($linkedseats as $key => $value)
   @if($value->price && $value->price >= 0)
   @else
   <?php $all--; ?>
   @endif
   @endforeach
   <div class="container">
   @if(!$unilever)
      <h2 class="section-title">{{$title}}</h2>
   @else
      <h2 class="section-title"> Subscribe for 1 year now </h2>
   @endif
      
      <h3> {!! $body !!} </h3>
      <div class="row row-flex row-flex-15">
         @if(isset($linkeddata))
         <?php $alumni = 1;    $special = true; $regular = false; ?>
            @foreach($linkedseats as $key => $value)
               @if(isset($value->type) && $value->type != 0)
                  @if($value->type == 1)
                     @if(isset($value->stock))
                        @if(isset($value->ticket['c_fields']['dropdown_boolean'][0]) && $value->ticket['c_fields']['dropdown_boolean'][0]['value'] == 1)
                           <?php $earlybird = true; $special = false; ?>
                        @else
                           <?php $earlybird = false;  $regular = true;?>
                        @endif
                        @if($earlybird && $value->stock <= 0 )
                           <?php $special = true;  ?>
                        @elseif(isset($value->ticket['c_fields']['dropdown_boolean'][1]) && $value->ticket['c_fields']['dropdown_boolean'][1]['value'] == 1 && !$special )
                           <?php $special = true;  ?>
                        @else

                        <div class="col-4 col-sm-12 @if($unilever) mar-auto @endif">
                        <?php
                           if (isset($value->price)) {
                              $price = $value->price;
                           } else { $price = 0; }
                           
                           ?>
                           <div class="ticket-box-wrapper">
                              <div class="ticket-box">
                                 <h3 class="@if(!$alumni) special-ticket  @endif">{{ $value->ticket['header'] }} <span> €{{$price}} </span></h3>
                                 <div class="ticket-box-content">
                                    <ul class="seat-features">
                                       <li>{{ $value->ticket['c_fields']['simple_text'][0]['value'] }}</li>
                                       <li>{{ $value->ticket['c_fields']['simple_text'][1]['value'] }}</li>
                                       <li>{{ $value->ticket['c_fields']['simple_text'][2]['value'] }}</li>
                                       <li>{{ $value->ticket['c_fields']['simple_text'][3]['value'] }}</li>
                                       <li>{{ $value->ticket['c_fields']['simple_text'][4]['value'] }}</li>
                                       <li>{{ $value->ticket['c_fields']['simple_text'][5]['value'] }}</li>
                                    </ul>
                                 </div>
                                 
                                 <div class="ticket-box-price">
                                    <span class="ticket-price hidden-xs">€{{$price}}</span>
                                    <span class="ticket-infos"> {{ $value->ticket['subtitle'] }}</span>
                                   
                                    <span class="ticket-infos hidden-xs">@if(!$alumni && ($content->view_tpl!='elearning_english')) {{ $value->stock }} seats remaining @else &nbsp; @endif</span> 
                                 </div>
                                 <div class="ticket-box-actions">
                                    @if($dis > 0 && $dis != $value->ticket_id && !$alumni)
                                       @if($value->stock == 0)
                                          <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                                       @else
                                          <div class="btn btn--lg btn--secondary btn--completed" >AVAILABLE SOON</div>
                                       @endif
                                    @else
                                       @if(isset($value->ticket['c_fields']['dropdown_boolean'][1]) && $value->ticket['c_fields']['dropdown_boolean'][1]['value'] == 1)
                                          @if($value->stock == 0)
                                             <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                                          @else

                                             <div class="ticket-actions-wrapper">
                                                <a href="#" class="btn btn--lg btn--primary btn-ticket--dropdown">ENROLL NOW</a>
                                                <ul class="tickets-dropdown">
                                                   @if(!$alumni) 
                                                   <li><a href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 1 ]) }}" class="btn btn-add btn--lg btn--primary">UNEMPLOYED</a></li>
                                                   <li><a href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 2 ]) }}" class="btn btn-add btn--lg btn--primary">STUDENT</a></li>
                                                   <li><a href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 5 ]) }}" class="btn btn-add btn--lg btn--primary">GROUP</a></li>
                                                   @endif
                                                </ul>
                                             </div>
                                          @endif
                                       @else
                                          @if($value->stock == 0)
                                             <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                                          @else
                                             @if($value->price == 0)
                                                <div class="ticket-actions-wrapper">
                                                   <a href="#" class="btn btn--lg btn--primary btn-ticket--dropdown">ENROLL NOW</a>
                                                   <ul class="tickets-dropdown">
                                                      <li><a class="btn btn-add btn--lg btn--primary" title="ENROLL NOW" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 3 ]) }}">KNOWCRUNCH ALUMNI</a></li>
                                                      <li><a class="btn btn-add btn--lg btn--primary" title="ENROLL NOW" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 0 ]) }}">OTHER</a></li>
                                                   </ul>
                                                </div>
                                                @else
                                                   @if($alumni)

                                                      @if($unilever)
                                                      <div class="ticket-actions-wrapper">
                                                         <a href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 7 ]) }}" class="btn btn-add btn--lg btn--primary ">ENROLL NOW</a>
                                                      </div>
                                                      @else

                                                      <div class="ticket-actions-wrapper">
                                                         <a href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 3 ]) }}" class="btn btn-add btn--lg btn--primary ">ENROLL NOW</a>
                                                      </div>

                                                      @endif
                                                   @else
                                                      <div class="ticket-actions-wrapper">
                                                         <a class="btn btn-add btn--lg btn--primary"  title="ENROLL NOW" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 0 ]) }}">ENROLL NOW</a>
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

                        @endif
                     @endif
                  @endif
               @endif
               <?php $alumni= 0?>
            @endforeach
         @endif
      </div>
   </div>
</section>
@endif
<!-- SEATS END -->
@php
    $tickets = $dynamic_page_data["tickets"] ?? null;
    $showSpecial = $dynamic_page_data["showSpecial"] ?? null;

    $event = $dynamic_page_data["event"] ?? null;
    $sections = $dynamic_page_data["sections"] ?? null;
    $showAlumni = $dynamic_page_data["showAlumni"] ?? null;
    $estatus = $event->status ?? null;

@endphp

<?php
   $title = '';
   $body = '';
   if(isset($sections['tickets'])){
      $title = $sections['tickets']->first()->title;
      $body = $sections['tickets']->first()->description;
   }
?>

@if ($tickets && !$event->isFree())
    @if ($estatus == App\Model\Event::STATUS_OPEN || $estatus == App\Model\Event::STATUS_SOLDOUT || $estatus == App\Model\Event::STATUS_WAITING)
        <section id="seats" class="section-tickets">
        <div class="container">
                <h2 class="section-title">{{$title}}</h2>
                <div class="row row-flex row-flex-15">
                    <?php
                    $early = false;
                    $index = 0;

                    foreach($tickets as $key => $ticket) {

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
                        $index = $index + 1;
                    }

                    $numberOfItems = $index;
                    if ($index) {
                        $columns = (12 / $index);
                    } else {
                        $columns = 12;
                    }
                    $columnClass = "col-md-" . $columns;

                    $early = false;
                    $index = 0;
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
                            $index = $index + 1;

                            $justifyClass = "justify-content-center";
                            if ($numberOfItems > 1) {
                                //echo($index . " " . $numberOfItems);
                                if ($index == 1) {
                                    $justifyClass = 'justify-content-end-2';
                                }
                                if ($index == ($numberOfItems)) {
                                    $justifyClass = 'justify-content-start-2';
                                }
                            }
                        ?>
                        <div class="{{$columnClass}} col-sm-12 d-flex book-ticket-boxes mb-4 {{$justifyClass}}" >
                            <div class="ticket-box-wrapper" style="width: 100%;">
                                <div class="ticket-box">
                                    <h3 class="@if($ticket['type'] != 'Alumni') special-ticket @endif">{{ empty($ticket['public_title']) ?  $ticket['type'] : $ticket['public_title'] }} <span> €{{$ticket['pivot']['price']}} </span></h3>
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
                                        <span class="ticket-infos hidden-xs">@if($ticket['type'] != 'Alumni' && ($event->view_tpl!='elearning_event')) {{ $ticket['pivot']['quantity'] }} seats remaining @else &nbsp; @endif</span>
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
                                                        @if($ticket['pivot']['quantity'] >= 2)<li><a href="{{ route('cart.add-item', [ $event->id, $ticket['id'], 5 ]) }}"  class="btn btn-add btn--lg btn--primary">GROUP</a></li>@endif
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
    @endif
@endif

@php
$show_awards = false;
if(isset($event)){
    foreach($event->delivery as $delivery){
        if($delivery->id == \App\Model\Delivery::CLASSROM_TRAINING || $delivery->id == \App\Model\Delivery::VIDEO_TRAINING || $delivery->id == \App\Model\Delivery::VIRTUAL_CLASS_TRAINING){
            $show_awards = true;
        }
    }
}
@endphp

@if($show_awards)
<div class="background-blue_gradient"><div class="container-fluid"><div class="row"><div class="col-md-12"><img width="100%" height="100%" src="{{cdn('/awards/knowcrunch-awards-2024.png')}}" alt="Knowcrunch awards" class="center"></div></div></div></div>
@endif

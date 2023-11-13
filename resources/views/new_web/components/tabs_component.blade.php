@php
    $tabs = [];
    foreach ($column->template->inputs as $input){
        $tabs[$input->key] = [
            "value" => $input->value ?? "",
            "tabs" => $input->tabs ?? "",
        ];
    }

    $event = $dynamic_page_data["event"] ?? null;
    $is_event_paid = $dynamic_page_data["is_event_paid"] ?? null;
    $is_joined_waiting_list = $dynamic_page_data["is_joined_waiting_list"] ?? null;
    $estatus = $event->status ?? null;
    $is_event_expired = $dynamic_page_data["is_event_expired"] ?? null;

    $freeEvent = isset($dynamic_page_data['info']['payment_method']) && $dynamic_page_data['info']['payment_method'] == 'free' ? true : false;

    function checkTabContent($tab, $dynamic_page_data, $tabs) {
        $tab = strtolower($tab);
        foreach ($tabs["tabs"]["value"] as $tab_content) {

            if ($tab_content->tabs_tab == $tab) {
                if ($tab == "faq") {
                    $tab = "questions";
                }
                if ($tab == "overview") {
                    return true;
                }
                if (isset($dynamic_page_data["sections"][$tab])) {
                    if ($dynamic_page_data["sections"][$tab]->first()->visible) {
                        return true;
                    } else {
                        //dd($dynamic_page_data["sections"][$tab]->first());
                    }
                } else {
                    return true;
                }
            }
        }
        return false;
    }

@endphp

<div class="section-course-tabs">
    <div class="content-wrapper">
        <div class="tabs-wrapper fixed-tab-controls">
            <div class="tab-controls @if(count(get_tickers()) != 0) {{ 'has_ticker'}} @else {{''}} @endif">
                <div class="container tabs-container">
                            <a href="#" class="mobile-tabs-menu">Details</a>
                            <ul class="clearfix tab-controls-list">
                                @if(isset($sections['overview']) && $sections['overview']->first())<li><a href="#overview" class="active">{{$sections['overview']->first()->tab_title}}</a></li>@endif

                                @if ($estatus == 0 || $estatus == 2 || $estatus == 5)
                                    @foreach ($tabs["tabs"]["tabs"] as $index=>$tab)
                                        @if(checkTabContent($tab, $dynamic_page_data, $tabs))
                                            <li><a href="#{{Illuminate\Support\Str::slug($tab)}}" class="{{$index == 0 ? "active" : "" }}">{{$tab}}</a></li>
                                        @endif
                                    @endforeach
                                @else
                                    <li><a href="#{{Illuminate\Support\Str::slug("Overview")}}" class="active">Overview</a></li>
                                    <li><a href="#{{Illuminate\Support\Str::slug("Topics")}}" class="">Topics</a></li>
                                    <li><a href="#{{Illuminate\Support\Str::slug("Instructors")}}" class="">Instructors</a></li>
                                @endif
                            </ul>
                            
                            @if ($event)
                                {{--@if($event->view_tpl == "elearning_free")--}}
                                @if($freeEvent)
                            
                                    @if($is_event_paid==0 && !Auth::user())             
                                        <a href="{{ route('cart.add-item', [ $event->id,'free', 8 ]) }}" class="btn btn--lg btn--secondary  go-to-href">ENROLL FOR FREE</a>
                                    @elseif($is_event_paid==0 && Auth::user())
                                        <a href="{{ route('enrollForFree',  $event->id) }}" class="btn btn--lg btn--secondary go-to-href">ENROLL FOR FREE</a>
                                    @elseif($is_event_paid==1 && Auth::user())
                                        <a href="/myaccount/elearning/{{ $event['title'] }}" class="btn btn--md btn--secondary">WATCH NOW</a>
                                    @elseif(($is_event_paid==0 && Auth::user()) || ($is_event_paid==1 && $is_event_expired == 1))
                                        <a href="{{ route('enrollForFree',  $event->id) }}" class="btn btn--lg btn--secondary go-to-href">ENROLL FOR FREE</a>                                   
                                    @endif
                                @elseif($estatus == 0 && !$is_event_paid)
                                    @if($is_event_paid==0 && $event['view_tpl'] == 'event_free_coupon')
                                        <a href="javascript:void(0)" id="open-code-popup" class="btn btn--lg btn--primary">ENROLL NOW</a>
                                    @else
                                        <a href="#seats" class="btn btn--lg btn--primary go-to-href">ENROLL NOW</a>
                                    @endif
                                @elseif($estatus == 5 && !$is_joined_waiting_list && !$is_event_paid)
                                    <a href="{{ route('cart.add-item', [ $event->id,'waiting', 8 ]) }}" class="btn btn--lg btn--primary go-to-href elearning-free">JOIN WAITING LIST</a>
                                @elseif($estatus != 3 && $estatus != 5 && $estatus != 1 && !$is_event_paid)
                                    <a href="#seats" class="btn btn--lg btn--primary go-to-href go-to-href soldout">SOLD OUT</a>
                                @endif
                            @endif

                </div>
            </div>

            @if ($estatus == 1 || $estatus == 3)
                @php
                    array_shift($tabs["tabs"]["tabs"]);
                @endphp
            @endif

            <div class="tabs-content">
                @foreach ($tabs["tabs"]["tabs"] as $index => $tab)
                    <div id="{{Illuminate\Support\Str::slug($tab)}}" class="p-0 tab-content-wrapper {{ Illuminate\Support\Str::slug($tab) == "overview" ? 'active-tab' : '' }}">
                        @foreach ($tabs["tabs"]["value"] as $data)
                            @if ($data->tabs_tab == $tab)
                                @include("new_web.layouts.rows")
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

            @if ($estatus == 1 || $estatus == 3)
                <div class="container">
                <div  class="course-overview clearfix mt-5 mb-5">
                    <div class="course-tab-text" itemprop="abstract">

                        <span class="completed">The event is completed</span>
                        <p style="display:none">The best digital &amp; social media diploma with a long track record trusted by top executives, agencies, brands and corporations is completed.</p>
                        <p >Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}.</a> @else upcoming events in this city. @endif</p>
                    </div>
                </div>
                </div>
            @endif

        </div>
    </div>
</div>

@if($event)
<div class="code-popup-wrapper">
   <div class="code-popup">
      <a href="javascript:void(0)" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
      <div class="heading">
         <span>G-app Registration</span>
         <p>This event is only for students who have a special coupon by the Germanos G-app.</p>
      </div>
      <div class="alert-outer" hidden>
      <div class="container">
         <div class="alert-wrapper error-alert">
            <div class="alert-inner">
               <p id="code-error"></p>
            </div>
         </div>
      </div>
      <!-- /.alert-outer -->
	</div>
         <div class="input-wrapper input-wrapper--text">
               <input type="text" onblur="this.placeholder = 'Please type your code here'" onfocus="this.placeholder = ''" placeholder="Please type your code here" id="event-code" name="event-code">
         </div>
         <input type="hidden" value="{{$event->id}}" name="event">
         <input id="code-popup-submit" type="submit"  value="Confirm">
   </div><!-- ./code-popup -->
</div><!-- ./code-popup-wrapper -->
@endif

@push('components-scripts')
    <script type="text/javascript">
        $(document).ready(function() {


            if(hasCountdown){
                $('.tab-controls').addClass('has_countdown')
            }





            $('#open-code-popup').click(function(e) {
                $('.code-popup-wrapper').addClass('active');
            });

            $(".close-btn").click(function(){
                $('.code-popup-wrapper').removeClass('active');
                $('.alert-outer').hide()
            });

            $('#code-popup-submit').click(function(e) {
                checkCode();
            });

            function checkCode(){
                $('.alert-outer').hide()
                var eventCode = $('#event-code').val();
                var event = $('input[name=event]').val();
                $.ajax({ url: '/checkCode', type: "post",
                    data: {eventCode:eventCode, event:event},
                    success: function(data) {
                        if(!data['success']){
                        var p = document.getElementById('code-error').textContent = data['message'];
                        var img = document.createElement('img');
                        img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-error-alert.svg" )
                        img.setAttribute('alt',"Info Alert" )
                        $('#code-error').append(img);
                        $('.alert-outer').show()
                        }else{
                            window.location.replace(data['redirect']);
                        }
                    },
                });
            }
        });
    </script>
@endpush

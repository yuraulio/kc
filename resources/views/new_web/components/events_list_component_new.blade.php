@php
    use App\Library\CMS;
    use App\Model\Slug;
    $slug_model = Slug::whereSlug($page->slug)->first();

    if ($slug_model) {
        $data = CMS::getDeliveryData($slug_model->slugable);

        $openlist = $data['openlist'];
        $delivery = $data['delivery'];
        $completedlist = $data['completedlist'];

        $elern = false;
        $diplomas = false;
        $certificates = false;

        if ($delivery['slugable']['slug'] === 'video-on-demand-courses') {
            $elern = true;
        }
    }
@endphp

@if ($slug_model)

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
    <div class="filters-wrapper mb-5">

        <div id="upcoming" class="filter-tab active-tab">

            @if(isset($openlist) && count($openlist) > 0)
                <?php
                    $countopen = count($openlist);
                    $lastmonth1 = '';
                ?>

                @foreach($openlist as $key => $row)
                    @if($key===0)
                        <div style="height:100px"></div>
                    @endif

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
                                    <?php


                                        $info = $row->event_info->formedData();

                                    ?>
                                    <h2><a href="{{env('NEW_PAGES_LINK') . '/' . $slug }}">{{ $row->title}}</a></h2>
                                    <div class="bottom">

                                        {{--@if ($row->summary1->where('section','date')->first() && $row->summary1->where('section','date')->first()->title)
                                            <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> {{$row->summary1->where('section','date')->first()->title}}  </div>
                                        @endif--}}





                                        @if(isset($info['hours']['visible']['list']) && $info['hours']['visible']['list'] && $info['hours']['hour'] != null)
                                            <div class="duration">@if(isset($info['hours']['icon']['path']) && $info['hours']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['hours']['icon']['path'])}}" alt="{{$info['hours']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif{{ $info['hours']['hour'] }} {{ ($info['hours']['text'] != null) ? $info['hours']['text'] : '' }}</div>
                                        @endif


                                        @if(isset($info['language']['visible']['list']) && $info['language']['visible']['list'] && $info['language']['text'] != null)
                                            <div class="duration">@if(isset($info['language']['icon']['path']) &&  $info['language']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['language']['icon']['path'])}}" alt="{{ $info['language']['icon']['alt_text'] }}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Language.svg" alt=""> @endif {{ $info['language']['text'] }}</div>
                                        @endif


                                        @if(isset($info['certificate']['visible']['list']) && $info['certificate']['visible']['list'] && $info['certificate'] != null && $info['certificate']['type'] != null)
                                        <div class="duration">@if(isset($info['certificate']['icon']['path']) && $info['certificate']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['certificate']['icon']['path'])}}" alt="{{$info['certificate']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt=""> @endif {{ $info['certificate']['type'] }}</div>
                                        @endif

                                        @if(isset($info['students']['visible']['list']) && $info['students']['visible']['list'] && get_sum_students_course($row->category->first()) > $info['students']['number'])
                                            <div class="duration">@if(isset($info['students']['icon']['path']) && $info['students']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['students']['icon']['path'])}}" alt="{{$info['students']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Group_User.1.svg" alt=""> @endif {{ get_sum_students_course($row->category->first()) }} {{ $info['students']['text'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="right">
                                    <?php
                                        if (isset($row['ticket']->where('type','Early Bird')->first()->pivot->price) &&
                                            $row['ticket']->where('type','Early Bird')->first()->pivot->price > 0 &&
                                            $row['ticket']->where('type','Early Bird')->first()->pivot->quantity > 0 &&
                                            $row['ticket']->where('type','Early Bird')->first()->pivot->active) {

                                            $price = $row['ticket']->where('type','Early Bird')->first()->pivot->price;

                                        } else if(isset($row['ticket']->where('type','Special')->first()->pivot->price) &&
                                            $row['ticket']->where('type','Special')->first()->pivot->price > 0 &&
                                            $row['ticket']->where('type','Special')->first()->pivot->quantity > 0 &&
                                            $row['ticket']->where('type','Special')->first()->pivot->active){

                                            $price = $row['ticket']->where('type','Special')->first()->pivot->price;
                                        } else if(isset($row['ticket']->where('type','Regular')->first()->pivot->price) &&
                                            $row['ticket']->where('type','Regular')->first()->pivot->price > 0 &&
                                            $row['ticket']->where('type','Regular')->first()->pivot->quantity > 0 &&
                                            $row['ticket']->where('type','Regular')->first()->pivot->active){

                                            $price = $row['ticket']->where('type','Regular')->first()->pivot->price;
                                        } else {
                                            $price = 0;
                                        }
                                    ?>

                                    @if(isset($row['event_info']['course_payment_method']) && $row['event_info']['course_payment_method'] == 'free')
                                        <div class="price">free</div>
                                        <a href="{{ $slug }}" class="btn btn--secondary btn--md">Enroll For Free</a>
                                    @elseif($row->view_tpl == 'elearning_pending')
                                        <div class="price">Pending</div>
                                        <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
                                    @elseif($row['status'] == 5)
                                        <div class="price">Course coming soon</div>
                                        <a href="{{ $slug }}" class="btn btn--secondary btn--md">JOIN WAITING LIST</a>
                                    @else
                                        <div class="price">from €{{$price}}</div>
                                        <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @else
                        <?php
                            $pubdate = $row->launch_date ? $row->launch_date :  $row->published_at;
                            $chmonth = date('m', strtotime($pubdate));
                            $month = date('F Y', strtotime($pubdate));

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
                                        } else {
                                            $slug = '';
                                        }
                                    ?>
                                    <?php


                                        $info = $row->event_info->formedData();

                                    ?>
                                    <h2><a href="{{env('NEW_PAGES_LINK') . '/' .  $slug }}">{{ $row->title}}</a></h2>
                                    <div class="bottom">
                                        @if(isset($row['city']))
                                            @foreach($row['city'] as $city)
                                                <a href="{{ env('NEW_PAGES_LINK') . '/' .  $city->slugable->slug }}" class="city " title="{{ $city->name }}">
                                                <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $city->name }}</a>
                                            @endforeach
                                        @endif


                                        @if(isset($info['hours']['visible']['list']) && $info['hours']['visible']['list'] && $info['hours']['hour'] != null)
                                            <div class="expire-date">@if(isset($info['hours']['icon']['path']) && $info['hours']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['hours']['icon']['path'])}}" alt="{{$info['hours']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif{{ $info['hours']['hour'] }} {{ ($info['hours']['text'] != null) ? $info['hours']['text'] : '' }}</div>
                                        @endif

                                        @if(isset($info['language']['visible']['list']) && $info['language']['visible']['list'] && $info['language']['text'] != null)
                                            <div class="language">@if(isset($info['language']['icon']['path']) && $info['language']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['language']['icon']['path'])}}" alt="{{$info['language']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="theme/assets/images/icons/Language.svg" alt=""> @endif {{ $info['language']['text'] }}</div>
                                        @endif

                                        @if(isset($info['certificate']['visible']['list']) && $info['certificate']['visible']['list'] && $info['certificate'] != null && $info['certificate']['type'] != null)
                                        <div class="certification_type">@if(isset($info['certificate']['icon']['path']) && $info['certificate']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['certificate']['icon']['path'])}}" alt="{{$$info['certificate']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt=""> @endif {{ $info['certificate']['type'] }}</div>
                                        @endif

                                        @if(isset($info['students']['visible']['list']) && $info['students']['visible']['list'] && get_sum_students_course($row->category->first()) > $info['students']['number'])
                                            <div class="students">@if(isset($info['students']['icon']['path']) && $info['students']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['students']['icon']['path'])}}" alt="{{$info['students']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Group_User.1.svg" alt=""> @endif {{ get_sum_students_course($row->category->first()) }} {{ $info['students']['text'] }}</div>
                                        @endif



                                        @if(isset($info['inclass']['dates']['visible']['list']) && $info['inclass']['dates']['visible']['list'] && $info['inclass']['dates']['text'] != null)

                                            <div class="dates">@if(isset($info['inclass']['dates']['icon']['path']) && $info['inclass']['dates']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['inclass']['dates']['icon']['path'])}}" alt="{{$info['inclass']['dates']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif {{ $info['inclass']['dates']['text'] }}</div>

                                        @endif
                                        @if(isset($info['inclass']['days']['visible']['list']) && $info['inclass']['days']['visible']['list'] && $info['inclass']['days']['text'] != null)
                                            <div class="days">@if(isset($info['inclass']['days']['icon']['path']) && $info['inclass']['days']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['inclass']['days']['icon']['path'])}}" alt="{{$info['inclass']['days']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif {{ $info['inclass']['days']['text'] }}</div>
                                        @endif

                                        @if(isset($info['inclass']['times']['visible']['list']) && $info['inclass']['times']['visible']['list'] && $info['inclass']['times']['text'] != null)

                                            <div class="times">@if(isset($info['inclass']['times']['icon']['path']) && $info['inclass']['times']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['inclass']['times']['icon']['path'])}}" alt="{{$info['inclass']['times']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif {{ $info['inclass']['times']['text'] }}</div>

                                        @endif

                                    </div>
                                </div>
                                <div class="right">
                                    <?php
                                        if (isset($row['ticket']->where('type','Early Bird')->first()->pivot->price) &&
                                            $row['ticket']->where('type','Early Bird')->first()->pivot->price > 0 &&
                                            $row['ticket']->where('type','Early Bird')->first()->pivot->quantity > 0 &&
                                            $row['ticket']->where('type','Early Bird')->first()->pivot->active) {

                                            $price = $row['ticket']->where('type','Early Bird')->first()->pivot->price;

                                        }else if(isset($row['ticket']->where('type','Special')->first()->pivot->price) &&
                                            $row['ticket']->where('type','Special')->first()->pivot->price > 0 &&
                                            $row['ticket']->where('type','Special')->first()->pivot->quantity > 0 &&
                                            $row['ticket']->where('type','Special')->first()->pivot->active){

                                            $price = $row['ticket']->where('type','Special')->first()->pivot->price;

                                        }else if(isset($row['ticket']->where('type','Regular')->first()->pivot->price) &&
                                            $row['ticket']->where('type','Regular')->first()->pivot->price > 0 &&
                                            $row['ticket']->where('type','Regular')->first()->pivot->quantity > 0 &&
                                            $row['ticket']->where('type','Regular')->first()->pivot->active){

                                            $price = $row['ticket']->where('type','Regular')->first()->pivot->price;
                                        } else {
                                            $price = 0;
                                        }
                                    ?>
                                    <?php $etstatus = 0 ?>
                                    <?php //dd($row['status']); ?>
                                    @if(isset($row['status']))
                                        <?php $etstatus = $row['status']; ?>
                                    @endif

                                    @if(isset($row['event_info']['course_payment_method']) && $row['event_info']['course_payment_method'] == 'free')
                                    <div class="price">free</div>
                                    <a href="{{ $slug }}" class="btn btn--secondary btn--md">Enroll For Free</a>
                                    @elseif($row->view_tpl == 'elearning_pending')
                                    <div class="price">Pending</div>
                                    <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
                                    @elseif($row['status'] == 5)
                                        <div class="price">Course coming soon</div>
                                        <a href="{{ $slug }}" class="btn btn--secondary btn--md">JOIN WAITING LIST</a>
                                    @else
                                    <div class="price">from €{{$price}}</div>
                                    <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
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
                            $pubdate = $row->launch_date  && $row->launch_date != '1970-01-01' ? $row->launch_date :  $row->published_at;
                            $chmonth = date('m', strtotime($pubdate));
                            $month = date('F Y', strtotime($pubdate));
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
                                    <?php


                                       $info = $row->event_info->formedData();

                                   ?>
                                    <h2><a href="{{ $slug }}">{{ $row->title}}</a></h2>
                                    <div class="bottom">
                                        @if(isset($row['city']))
                                            @foreach($row['city'] as $city)
                                                <a href="{{ $city->slugable->slug }}" class="city " title="{{ $city->name }}">
                                                <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $city->name }}</a>
                                            @endforeach
                                        @endif

                                        @if(isset($info['hours']['visible']) && $info['hours']['visible']['list'] && isset($info['hours']['hour']) && $info['hours']['hour'] != null)
                                            <div class="expire-date">@if(isset($info['hours']['icon']) && $info['hours']['icon'] != null && $info['hours']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['hours']['icon']['path'])}}" alt="{{$info['hours']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif{{ $info['hours']['hour'] }} {{ ($info['hours']['text'] != null) ? $info['hours']['text'] : '' }}</div>
                                        @endif

                                        @if(isset($info['language']['visible']) && $info['language']['visible']['list'] && isset($info['language']['text']) && $info['language']['text'] != null)
                                            <div class="language">@if(isset($info['language']['icon']) && $info['language']['icon'] != null && $info['language']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['language']['icon']['path'])}}" alt="{{$info['language']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Language.svg" alt=""> @endif {{ $row['event_info']['course_language'] }}</div>
                                        @endif

                                        @if(isset($info['certificate']['visible']) && $info['certificate']['visible']['list'] && isset($info['certificate']) && $info['certificate'] != null && $info['certificate']['type'] != null)
                                        <div class="certification_type">@if(isset($info['certificate']['icon']) && isset($info['certificate']['icon']) && $info['certificate']['icon'] != null && $info['certificate']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['certificate']['icon']['path'])}}" alt="{{$info['certificate']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt=""> @endif {{ $row['event_info']['course_certification_type'] }}</div>
                                        @endif

                                        @if(isset($info['students']['visible']) && $info['students']['visible']['list'] && get_sum_students_course($row->category->first()) > $info['students']['number'])
                                            <div class="students">@if(isset($info['students']['icon']) && isset($info['students']['icon']) && $info['students']['icon'] != null && $info['students']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['students']['icon']['path'])}}" alt="{{$info['students']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Group_User.1.svg" alt=""> @endif {{ get_sum_students_course($row->category->first()) }} {{ $row['event_info']['course_students_text'] }}</div>
                                        @endif



                                        @if(isset($info['inclass']['dates']) && $info['inclass']['dates'] != null && $info['inclass']['dates']['visible']['list'] && isset($info['inclass']['dates']['text']) && $info['inclass']['dates']['text'] != null)

                                            <div class="dates">@if(isset($info['inclass']['dates']['icon']) && isset($info['inclass']['dates']['icon']['path']) && $info['inclass']['dates']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['inclass']['dates']['icon']['path'])}}" alt="{{$info['inclass']['dates']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif {{ $info['inclass']['dates']['text'] }}</div>

                                        @endif
                                        @if(isset($info['inclass']['days']) && $info['inclass']['days'] != null && $info['inclass']['days']['visible']['list'] && $info['inclass']['days']['text'] != null)
                                            <div class="dates">@if(isset($info['inclass']['days']['icon']) && isset($info['inclass']['days']['icon']['path']) && $info['inclass']['days']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['inclass']['days']['icon']['path'])}}" alt="{{$info['inclass']['days']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif {{ $info['inclass']['days']['text'] }}</div>
                                        @endif

                                        @if(isset($info['inclass']['times']) && $info['inclass']['times'] != null && $info['inclass']['times']['visible']['list'] && isset($info['inclass']['times']['text']) && $info['inclass']['times']['text'] != null)

                                            <div class="times">@if(isset($info['inclass']['times']['icon']) && $info['inclass']['times']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['inclass']['times']['icon']['path'])}}" alt="{{$info['inclass']['times']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif {{ $info['inclass']['times']['text'] }}</div>

                                        @endif


                                    </div>
                                </div>
                                <div class="right">
                                    <?php
                                        if (isset($row['ticket']->where('type','Early Bird')->first()->pivot->price) &&
                                        $row['ticket']->where('type','Early Bird')->first()->pivot->price > 0 &&
                                        $row['ticket']->where('type','Early Bird')->first()->pivot->quantity > 0 &&
                                        $row['ticket']->where('type','Early Bird')->first()->pivot->active) {
                                            $price = $row['ticket']->where('type','Early Bird')->first()->pivot->price;
                                        }else if(isset($row['ticket']->where('type','Special')->first()->pivot->price) &&
                                        $row['ticket']->where('type','Special')->first()->pivot->price > 0 &&
                                        $row['ticket']->where('type','Special')->first()->pivot->quantity > 0 &&
                                        $row['ticket']->where('type','Special')->first()->pivot->active){
                                            $price = $row['ticket']->where('type','Special')->first()->pivot->price;
                                        }else if(isset($row['ticket']->where('type','Regular')->first()->pivot->price) &&
                                        $row['ticket']->where('type','Regular')->first()->pivot->price > 0 &&
                                        $row['ticket']->where('type','Regular')->first()->pivot->quantity > 0 &&
                                        $row['ticket']->where('type','Regular')->first()->pivot->active){
                                            $price = $row['ticket']->where('type','Regular')->first()->pivot->price;
                                        }
                                        else {
                                            $price = 0;
                                        }
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
@endif

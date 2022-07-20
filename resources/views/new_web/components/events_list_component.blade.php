@php
    use App\Library\CMS;
    use App\Model\Slug;
    $slug_model = Slug::whereSlug($page->slug)->first();

    if ($slug_model) {
        $data = CMS::getDeliveryData($slug_model->slugable);

        $openlist = $data['openlist'];
        $delivery = $data['delivery'];
        $completedlist = $data['completedlist'];
        $sumStudentsCategories = $data['sumStudentsByCategories'];

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


                    {{--@if($row->view_tpl == 'elearning_event' || $row->view_tpl == 'elearning_greek' || $row->view_tpl == 'elearning_free' || $row->view_tpl == 'elearning_pending')--}}
                    @if($row->is_elearning_course())
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

                                        $info = $row->event_info();


                                    ?>
                                    <h2><a href="{{env('NEW_PAGES_LINK') . '/' . $slug }}">{{ $row->title}}</a></h2>
                                    <div class="bottom">

                                        @include('new_web.components.event_infos',['type' => 'elearning'])

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

                                    @if(isset($info['payment_method']) && $info['payment_method'] == 'free')
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

                                        $info = $row->event_info();

                                    ?>
                                    <h2><a href="{{env('NEW_PAGES_LINK') . '/' .  $slug }}">{{ $row->title}}</a></h2>
                                    <div class="bottom">

                                        @include('new_web.components.event_infos',['type' => 'inclass'])

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

                                    @if(isset($info['payment_method']) && $info['payment_method'] == 'free')
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

            
                    {{--@if($row->view_tpl != 'elearning_event' && $row->view_tpl != 'elearning_greek' && $row->view_tpl != 'elearning_free' && $row->view_tpl != 'elearning_pending')--}}
                    @if($row->is_inclass_course())
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

                                    <h2><a href="{{ $slug }}">{{ $row->title}}</a></h2>
                                    <div class="bottom">
                                        @if(isset($row['city']))
                                            @foreach($row['city'] as $city)
                                                <a href="{{ $city->slugable->slug }}" class="city " title="{{ $city->name }}">
                                                <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $city->name }}</a>
                                            @endforeach
                                        @endif

                                        @include('new_web.components.event_infos',['type' => 'inclass'])


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

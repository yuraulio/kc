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

    <div class="control-wrapper-filters">
        <div class="filters">
            <a href="#upcoming" class="{{ $elern ? '' : 'active' }}">{{ $elern ? 'FREE' : 'upcoming' }}</a>
            <a href="#past" class="{{ $elern ? 'active' : '' }}">{{ $elern ? 'PAID' : 'past' }}</a>
        </div>
    </div>

    <!-- ./dynamic-learning--subtitle -->
    <?php
    $countcompl = 0;
    $countopen = 0;
    $countsold = 0;
    ?>
    <div class="filters-wrapper mb-5">

        <div id="upcoming" class="filter-tab {{ $elern ? '' : 'active-tab' }}">
            @if(isset($openlist) && count($openlist) > 0)
                <?php
                    $countopen = count($openlist);
                    $lastmonth1 = '';
                ?>
                <div style="height:100px"></div>

                @foreach($openlist as $key => $row)
                    @if($row->is_elearning_course())
                        @include('new_web.components.lists.events_list.elearning_course_line', ['row' => $row])
                    @else
                        @include('new_web.components.lists.events_list.inclass_course_line', ['row' => $row, 'elern' => $elern])
                    @endif
                @endforeach
            @else
                <h2> No available courses for now </h2>
            @endif
        </div>

        <div id="past" class="filter-tab {{ $elern ? 'active-tab' : '' }}">
            @if(isset($completedlist) && count($completedlist) > 0)
                <div style="height:100px"></div>
                <?php
                    $countopen = count($completedlist);
                    $lastmonth1 = '';
                ?>
                @if ($elern)
                    @foreach($completedlist as $row)
                        @include('new_web.components.lists.events_list.elearning_course_line', ['row' => $row])
                    @endforeach
                @elseif($row->is_inclass_course())
                    @foreach($completedlist as $row)
                        <?php
                            $pubdate = $row->launch_date  && $row->launch_date != '1970-01-01' ? $row->launch_date :  $row->published_at;
                            $chmonth = date('m', strtotime($pubdate));
                            $month = date('F Y', strtotime($pubdate));
                            $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                                return $cartItem->id === $row->id;
                            });

                            $info = $row->event_info();

                            $dynamicPageData = [
                              'event' => $row,
                              'info' => $info,
                            ];
                            $title = \App\Library\PageVariables::parseText($row->title, null, $dynamicPageData);
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

                                    <h2><a href="{{ $slug }}">{{ $title }}</a></h2>
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
                                        }
                                        else {
                                            $price = 0;
                                        }
                                    ?>
                                    <?php $etstatus = 0 ?>
                                    <a href="{{ $slug }}" class="btn btn--secondary btn--md btn--completed">@if($row['status'] == App\Model\Event::STATUS_COMPLETED) {{'completed'}} @elseif($row['status'] == App\Model\Event::STATUS_SOLDOUT) {{'soldout'}} @endif</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
@endif

@php
    $instructor = [];
    foreach ($column->template->inputs as $input){
        $instructor[$input->key] = $input->value ?? "";
    }

    $data = $dynamic_page_data;
    $content = $data["content"];
    $instructorEvents = $data["instructorEvents"];


@endphp


<div class="pb-4 pt-4">
    @if(isset($instructorEvents) && count($instructorEvents) > 0)
        <div class="instructor-area instructor-courses">
            <h2>{{ $content->title }} {{ $content->subtitle }} participates in:</h2>
            <div class="dynamic-courses-wrapper">
                @foreach($instructorEvents as $key => $row)
                    @if(isset($row))
                        <?php $estatus = $row['status']; ?>
                        @if($estatus == 0 || $estatus == 2)
                            @if($row['view_tpl'] =='elearning_event' || $row['view_tpl'] =='elearning_greek' || $row['view_tpl'] =='elearning_event' || $row['view_tpl'] =='elearning_free')
                            <div class="item">
                                <div class="left">
                                    <h2>{{ $row['title'] }}</h2>
                                </div>
                                <div class="right right--no-price">
                                    <a href="{{ env('NEW_PAGES_LINK') . '/' .  $row['slugable']['slug'] }}" class="btn btn--secondary btn--md">Course Details</a>
                                </div>
                            </div>
                            @endif
                        @endif
                    @endif
                @endforeach
            </div>



            <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">

                @foreach($instructorEvents as $key => $row)
                    <?php $estatus = $row['status']; ?>
                    @if($estatus == 0 || $estatus == 2)
                        @if($row['view_tpl'] !='elearning_event' && $row['view_tpl'] !='elearning_greek' && $row['view_tpl'] !='elearning_free')
                            <div class="item">
                                <div class="left">
                                    <h2>{{ $row['title'] }}</h2>
                                    <?php
                                        if(isset($row['summary1']) && count($row['summary1']) >0){
                                            foreach($row['summary1'] as $sum){
                                                if($sum['section'] == 'date')
                                                    $date = $sum['title'];
                                            }
                                        }
                                    ?>

                                    <div class="bottom">
                                        @if(count($row['city']) > 0 )
                                            <a href="{{env('NEW_PAGES_LINK') . '/' .  $row['city'][0]['slugable']['slug'] }}" title="{{ $row['city'][0]['name'] }}" class="location">
                                                <img width="20" src="/theme/assets/images/icons/marker.svg" alt="">
                                                {{ $row['city'][0]['name'] }}
                                            </a>
                                        @endif
                                        @if (isset($date) && $date != '')
                                            <div class="duration">
                                                <img width="20" src="theme/assets/images/icons/icon-calendar.svg" alt="">
                                                {{ $date }}
                                            </div>
                                        @endif
                                        @if($row['hours'] && (is_numeric(substr($row['hours'], 0, 1))))
                                            <div class="expire-date">
                                                <img width="20" src="theme/assets/images/icons/Start-Finish.svg" alt="">
                                                {{ $row['hours'] }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                                <div class="right right--no-price">
                                    <a href="{{env('NEW_PAGES_LINK') . '/' .  $row['slugable']['slug'] }}" class="btn btn--secondary btn--md">Course Details</a>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    $( document ).ready(function() {

        if($('.dynamic-courses-wrapper').text() == '' && $('.dynamic-courses-wrapper.dynamic-courses-wrapper--style2').text() == ''){
            $('.instructor-area.instructor-courses').hide()
        }

    });
</script>


@php
    $hero = [];
    foreach ($column->template->inputs as $input){
        $hero[$input->key] = $input->value ?? "";
    }

    if ($column->template->dynamic) {
        $event = $dynamic_page_data["event"] ?? null;
        $info = $event['event_info1'];

        $image = cdn(get_image($event->mediable,'header-image')) ?? "";
        $title = $event->title ?? "";
        $subtitle = $event->subtitle ?? "";
        $small = false;
        $left = false;
        $sumStudentsByCategory = getCategoriesWithSumStudents();
    } else {
        $image = $hero["hero_image"]->url ?? "";
        $title = $hero["hero_title"] ?? "";
        $subtitle = $hero["hero_subtitle"] ?? "";
        $small = $hero['hero_small'] ?? null;
        $left = $hero['hero_left'] ?? null;
    }
@endphp

@if(!$column->template->dynamic && !$image) 
    <section class="section-hero section-hero-small" style="height: 140px;">
    
    </section>
@else

    <section class="section-hero {{ $small ? 'section-hero-small' : '' }}" style="background-image:url('{{ !$small ? $image : '' }}');" >
        @if (!$small)
            <div class="overlay"></div>
        @endif
        <div class="container">

            <div class="hero-message cms-rich-text-editor {{ $left ? 'section-hero-left' : '' }} {{ $column->template->dynamic ? ' dynamic-header ' : ''}}">
                <div class="container event-infos">
                    <div class="row">

                        <?php
                        $certificate_visible = (isset($info['course_certification_visible']) && $info['course_certification_visible'] != null) ? json_decode($info['course_certification_visible'],true) : null;
                        $hours_visible = (isset($info['course_hours_visible']) && $info['course_hours_visible'] != null) ? json_decode($info['course_hours_visible'],true) : null;
                        $students_visible = (isset($info['course_students_visible']) && $info['course_students_visible'] != null) ? json_decode($info['course_students_visible'],true) : null;
                        $course_enable = (isset($info['course_awards']) && $info['course_awards']) ? $info['course_awards'] : false;

                        ?>
                        @if(isset($certificate_visible['landing']) && $certificate_visible['landing'] && isset($info['course_certification_type']) && $info['course_certification_type'] != null)
                        <div class="col-auto certificate text-center">
                            {{ $info['course_certification_type'] }}
                        </div>
                        @endif
                        @if(isset($event['delivery'][0]['name']))
                        <div class="col-auto delivery text-center">
                            {{ $event['delivery'][0]['name'] }}
                        </div>
                        @endif
                        @if(isset($hours_visible['landing']) && $hours_visible['landing'] && isset($info['course_hours']) && $info['course_hours'] > 0)
                        <div class="col-auto hours text-center">
                            {{ $info['course_hours'] }} hours
                        </div>
                        @endif
                    </div>
                </div>
                @if($column->template->dynamic)
                    <h1>{{$title}}</h1>
                    <h2>{{$subtitle}}</h2>
                @else
                    {!! $title !!}
                @endif

                <?php
                    if(isset($students_visible['landing']) && $students_visible['landing'] && isset($event['category'][0]['id'])){
                        $sumStudents = $sumStudentsByCategory[$event['category'][0]['id']];
                    }else{
                        $sumStudents = null;
                    }

                    $hasStudentColumn = false;
                ?>
                <div class="row">
                    @if(isset($students_visible['landing']) && $students_visible['landing'] && isset($info['course_students_number']) && $info['course_students_text'])
                    <div class="col-12 col-md-auto students">
                        {{ $sumStudents + (int)$info['course_students_number'] }} {{ ((isset($info['course_students_text']) && $info['course_students_text'] != null) ? $info['course_students_text'] : '') }}
                    </div>
                    <?php $hasStudentColumn = true; ?>
                    @endif

                    @if($course_enable && isset($info['course_awards_text']) && $info['course_awards_text'])
                    <div class="col-12 col-md-2 @if($hasStudentColumn) offset-md-6 @else offset-md-10 @endif  awards">
                        <?php
                        $course_awards_icon = (isset($info['course_awards_icon']) && $info['course_awards_icon']) ? json_decode($info['course_awards_icon'], true) : null;
                        ?>

                        <div class="awards-landing-container">
                            <ul>

                            <li style="list-style:none !important;">
                            @if($course_awards_icon != null && $course_awards_icon['path'] != null)
                                <img class="info-icon-hero" src="{{ cdn($course_awards_icon['path'])}}" alt="{{(isset($course_awards_icon['alt_text']) && $course_awards_icon['alt_text'] != null ) ? $course_awards_icon['alt_text'] : '' }}">
                                {{--<img class="info-icon-hero" src="{{ cdn('uploads\originals\instructors\aivalis-apostolis-knowcrunch1-instructors-small.png') }}" alt="{{(isset($course_awards_icon['alt_text']) && $course_awards_icon['alt_text'] != null ) ? $course_awards_icon['alt_text'] : '' }}">--}}
                                <div class="info-text">
                                    <p class="">{{ $info['course_awards_text'] }}</p>
                                </div>

                            @else
                                <div class="info-text">
                                    <p class="">{{ $info['course_awards_text'] }}</p>
                                </div>
                            @endif
                            </li>
                            </ul>
                        </div>


                    </div>
                    @endif
                </div>
            </div>
        </div>

    </section>
@endif

@push('components-scripts')
    <script>
        document.getElementById('header').classList.add('header-transparent');
        var main = document.getElementById('main-area');
        main.id = "main";
    </script>
@endpush

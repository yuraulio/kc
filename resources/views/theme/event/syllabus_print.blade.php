<!-- SYLLABUS PRINT -->
{{--@inject('frontHelp', 'Library\FrontendHelperLib')--}}
<style>
    h1, h2 { font-family: Arial, Helvetica, sans-serif; }
    .patates {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 10px;
    }
    .patates td, .patates th {
        border: 1px solid #ddd;
        padding: 6px;
    }
    tr.even { background-color: #f2f2f2 !important; }
    .patates th {
        padding-top: 8px;
        padding-bottom: 8px;
        text-align: left;
        background: #1c4176;
        color: white;
    }
    .topic-section { margin-bottom: 20px; }
    h2.topic-title { font-size: 18px; margin: 6px 0; padding: 0; }
    .topic-desc { padding: 0 0 10px 0;}
</style>


<div id="section-topics" class="event-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1>{{ $content['title'] }}</h1>
                <h2>@if(count($content['city']) > 0) In {{ $content['city'][0]['name']}}@endif, @if($content->is_inclass_course()) In-class @endif</h2>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="clearfix"></div>
                            <div class="panel-group">
                                <?php //dd($eventtopics); ?>
                            @if(isset($eventtopics))
                            @foreach($eventtopics as $key => $value)
                            <?php //dd($value); ?>

                                    <div class="topic-section">
                                        <div class="panel-heading" role="tab" id="theading{{ $key }}">
                                            <h2 class="topic-title">{{ $key }}</h2>
                                        </div>
                                        <div id="tcollapse{{ $key }}">
                                            <div class="panel-body">

                                                <div class="topic-desc">{{$desc[$key]}}</div>
                                                <!-- TOPIC LESSONS HERE -->
                                                <table class="patates">
                                                    <thead>
                                                        <tr>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                        <th>Location</th>
                                                        <th>Type</th>
                                                        <th>Lesson</th>
                                                        <th>Instructor</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($value['lessons'] as $lke => $lvalue)
                                                    <?php //dd($lvalu); ?>
                                                         {{--@foreach($lvalu as $lkey => $lvalue)--}}

                                                        <tr >
                                                            <td><img height="10" src="theme/assets/img/calendar.svg" alt="Date" /> <?= date( "l d M Y", strtotime($lvalue['pivot']['time_starts']) ) ?></td>
                                                            <td><?= date( "l d M Y", strtotime($lvalue['pivot']['time_ends']) ) ?> ({!! $lvalue['pivot']['duration'] !!})</td>
                                                            <td>{{$lvalue['pivot']['room'] }}</td>

                                                            <td>{{-- $level --}} @if(count($lvalue['type']) > 0) {!! $lvalue['type'][0]['name'] !!} @endif</td>
                                                            <td>{{ $lvalue['title'] }}</td>
                                                            <td>{!! $instructors[$lvalue['pivot']['instructor_id']][0]['title'] !!} {!! $instructors[$lvalue['pivot']['instructor_id']][0]['subtitle'] !!}</td>
                                                        </tr>

                                                        {{--@endforeach--}}
                                                    @endforeach


                                                    </tbody>
                                                </table>

                                            </div>
                                        </div><!-- END PANEL -->
                                    </div>
                                @endforeach
                            @endif

                            </div><!-- END COL-12 -->
                        </div>
                    </div><!-- END ROW -->
                </div>
            </div>
        </div>

</div>

 <!-- TOPICS END -->

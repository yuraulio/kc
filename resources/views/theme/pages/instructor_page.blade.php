@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@include('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status])

<main id="main-area" role="main">
                
                <div class="instructor-wrapper">
                    <div class="container">

                        <div class="instructor-area instructor-profile">
                            <div class="row">
                                <div class="col4 col-xs-12">
                                    <div class="avatar-wrapper">


                                        <div class="avatar" alt="{{ $content->title }} {{ $content['subtitle'] }}" title="{{ $content->title }} {{ $content['subtitle'] }}"  style="background-image:url('{{ $frontHelp->pImg($content, 'instructors-testimonials') }}');"></div>
                                        <div class="social-links">
                                

                                            @if(isset($content['c_fields']['simple_text'][2]) && $content['c_fields']['simple_text'][2]['value'] != '')
                                             <a target="_blank" href="{{ $content['c_fields']['simple_text'][2]['value'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                             @if(isset($content['c_fields']['simple_text'][4]) && $content['c_fields']['simple_text'][4]['value'] != '')
                                             <a target="_blank" href="{{ $content['c_fields']['simple_text'][4]['value'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                             @if(isset($content['c_fields']['simple_text'][5]) && $content['c_fields']['simple_text'][5]['value'] != '')
                                             <a target="_blank" href="{{$content['c_fields']['simple_text'][5]['value']}}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                             @if(isset($content['c_fields']['simple_text'][3]) && $content['c_fields']['simple_text'][3]['value'] != '')
                                             <a target="_blank" href="{{ $content['c_fields']['simple_text'][3]['value'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                             @if(isset($content['c_fields']['simple_text'][6]) && $content['c_fields']['simple_text'][6]['value'] != '')
                                             <a target="_blank" href="{{ $content['c_fields']['simple_text'][6]['value'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col8 col-xs-12">
                                    <div class="text-area">

                                        <h1>{{ $content->title }} {{ $content->subtitle }}</h1>
                                        <h2>{{ $content['header'] }},@if($content['c_fields']['simple_text'][1]['value'] != '') <a target="_blank" title="{{ $content['c_fields']['simple_text'][1]['value'] }}" href="{{ $content['ext_url'] }}"> {{ $content['c_fields']['simple_text'][1]['value'] }}</a> @endif</h2>
                                        {!! $content->body !!}
                                    </div>
                                </div>
                            </div>
                        </div><!-- ./instructor-area -->

                        @if(count($instructorTeaches) >0)
                        <div class="instructor-area instructor-studies">
                            <h2>{{ $content->title }} {{ $content->subtitle }} teaches:</h2>
                            <ul>
                                @foreach($instructorTeaches as $teach)
                                <li><img width="25" src="{{cdn('/theme/assets/images/icons/graduate-hat.svg')}}" alt="">{{$teach}}</li>
                               
                                @endforeach
                            </ul>
                        </div><!-- ./instructor-area -->
                        @endif
                        @if(isset($events) && count($events) > 0)

                        <div class="instructor-area instructor-courses">
                            <h2>{{ $content->title }} {{ $content->subtitle }} participates in:</h2>

                            <div class="dynamic-courses-wrapper">

                            @foreach($events as $key => $row)
                       

                        
                            @if(isset($row['c_fields']['dropdown_select_status']['value']))

                                <?php    $estatus = $row['c_fields']['dropdown_select_status']['value']; ?>

                                @if($estatus == 0 || $estatus == 2)
                                <?php $view_tpl = PostRider\Content::select('view_tpl')->where('id',$row->id)->first() ;?>
                                @if($view_tpl['view_tpl'] =='elearning_english' || $view_tpl['view_tpl'] =='elearning_greek')
                                    <?php
                                        $location = [];
                                        $eventtype = [];
                                        if (isset($row->categories) && !empty($row->categories)) :
                                            foreach ($row->categories as $category) :
                                                if ($category->depth != 0 && $category->parent_id == 9) {
                                                    $location=$category;
                                                }
                                                if ($category->depth != 0 && $category->parent_id == 12) {
                                                    $eventtype=$category;
                                                    
                                                }
                                            endforeach;
                                        endif;


                                    ?>
                                <div class="item">
                                    <div class="left">
                                        <h2>{{ $frontHelp->pField($row, 'title') }}</h2>
                                        
                                        
                                       
                                    </div>
                                    <div class="right right--no-price">
                                        <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Course Details</a>
                                    </div>
                                </div><!-- ./item -->
                                @endif
                                @endif
                                @endif
                                @endforeach
                            </div><!-- ./dynamic-courses-wrapper -->


                            <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">

                            @foreach($events as $key => $row)
                       

                        
                            @if(isset($row['c_fields']['dropdown_select_status']['value']))
                                    
                                <?php    $estatus = $row['c_fields']['dropdown_select_status']['value']; ?>

                                {{--if($estatus == 0 || $estatus == 2)--}}
                                <?php $view_tpl = PostRider\Content::select('view_tpl')->where('id',$row->id)->first() ;?>
                                @if($view_tpl['view_tpl'] !='elearning_english' && $view_tpl['view_tpl'] !='elearning_greek')
                                    <?php
                                        $location = [];
                                        $eventtype = [];
                                        if (isset($row->categories) && !empty($row->categories)) :
                                            foreach ($row->categories as $category) :
                                                if ($category->depth != 0 && $category->parent_id == 9) {
                                                    $location=$category;
                                                }
                                                if ($category->depth != 0 && $category->parent_id == 12) {
                                                    $eventtype=$category;
                                                    
                                                }
                                            endforeach;
                                        endif;


                                    ?>
                                <div class="item">
                                    <div class="left">
                                        <h2>{{ $frontHelp->pField($row, 'title') }}</h2>
                                        
                                        <div class="bottom">
                                        @if(isset($location->name))<a href="{{ $location->slug }}" title="{{ $location->name }}" class="location"><img width="20" src="/theme/assets/images/icons/marker.svg" alt="">{{ $location->name }}</a> @endif
                                        @if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '')      <div class="duration"><img width="20" src="theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $row['c_fields']['simple_text'][0]['value'] }} </div>@endif
                                            @if($row['c_fields']['simple_text'][12] && (is_numeric(substr($row['c_fields']['simple_text'][12]['value'], 0, 1))))  <div class="expire-date"><img width="20" src="theme/assets/images/icons/Start-Finish.svg" alt="">{{ $row['c_fields']['simple_text'][12]['value'] }}</div>@endif
                                        </div>
                                       
                                    </div>
                                    <div class="right right--no-price">
                                        <a href="{{ $frontHelp->pSlug($row) }}" class="btn btn--secondary btn--md">Course Details</a>
                                    </div>
                                </div><!-- ./item -->
                                @endif
                                {{--endif--}}
                                @endif
                                @endforeach
                            </div><!-- ./dynamic-courses-wrapper -->
                         
                        </div><!-- ./instructor-area -->
                        
                      @endif
                    </div>
                </div><!-- ./instructor-wrapper -->

			<!-- /#main-area -->
			</main>

@endsection

@section('scripts')

@stop


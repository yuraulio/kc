@php
    use App\Model\Instructor;
    $instructors = Instructor::with('medias', 'slugable')->orderBy('subtitle', 'asc')->where('status', 1)->get();

    $instructors_display = [];
    foreach ($column->template->inputs as $input){
        $instructors_display[$input->key] = $input->value ?? "";
    }

    $type = $instructors_display['instructors_list']->id ?? null;
@endphp

@if(isset($instructors))
    <div class="section-course-tabs">
        <div class="content-wrapper">
            <div class="tabs-wrapper">
                <div class="tabs-content">
                    <div class="tab-content-wrapper active-tab p-0">
                        <div class="course-full-text">
                            <div class="instructors-wrapper row row-flex-23">
                                @foreach($instructors as $lkey => $lvalue)
                                    @if ($type == 2)
                                        {{-- grid view --}}
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                            <div class="instructor-box">
                                                <div class="instructor-inner">
                                                    <?php
                                                        $img = '';
                                                        $inst_url = $lvalue['slugable']['slug'];
                                                        $ext_url = $lvalue['ext_url'];
                                                        $fb = '';
                                                        $inst = '';
                                                        $twitter = '';
                                                        $med = '';
                                                        $pint = '';
                                                        $linkedIn = '';
                                                        $yt = '';
                                                        $name = $lvalue['title'] . ' ' . $lvalue['subtitle'];
                                                        $field1 = '';
                                                        $field2 =$lvalue['company'];

                                                        if(isset($lvalue['medias'])){
                                                            $img =  get_image($lvalue['medias'], 'instructors-testimonials');
                                                        }

                                                        if(isset($lvalue['header'])){
                                                            $field1 =  $lvalue['header'];
                                                        }

                                                        $socialMedia = json_decode($lvalue['social_media'],true);

                                                        if(isset($socialMedia['facebook'])){
                                                            $fb = $socialMedia['facebook'];
                                                        }

                                                        if(isset($socialMedia['twitter'])){
                                                            $twitter = $socialMedia['twitter'];
                                                        }

                                                        if(isset($socialMedia['instagram'])){
                                                            $inst = $socialMedia['instagram'];
                                                        }

                                                        if(isset($socialMedia['linkedin'])){
                                                            $linkedIn = $socialMedia['linkedin'];
                                                        }

                                                        if(isset($socialMedia['youtube'])){
                                                            $yt = $socialMedia['youtube'];
                                                        }
                                                    ?>
                                                    <div class="profile-img">
                                                        <a href="{{env('NEW_PAGES_LINK') . '/' . $lvalue['slugable']['slug']}}"><img src="{{cdn($img)}}"  title="{{$name}}" alt="{{$name}}"></a>
                                                    </div>
                                                    <h3><a href="{{env('NEW_PAGES_LINK') . '/' . $lvalue['slugable']['slug']}}">{{$name}}</a></h3>
                                                    <p>{{$field1}}, <a target="_blank" title="{{$field1}}" @if($ext_url!='') href="{{$ext_url}}"@endif>{{$field2}}</a>.</p>
                                                    <ul class="social-wrapper">
                                                        @if($fb != '')
                                                            <li><a target="_blank" href="{{$fb}}"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                                                        @endif
                                                        @if($inst !='')
                                                            <li><a target="_blank" href="{{$inst}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="16" alt="Visit"></a></li>
                                                        @endif
                                                        @if($linkedIn !='')
                                                            <li><a target="_blank" href="{{$linkedIn}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="16" alt="Visit"></a></li>
                                                        @endif
                                                        @if($pint !='')
                                                            <li><a target="_blank" href="{{$pint}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Pinterest.svg')}}" width="16" alt="Visit"></a></li>
                                                        @endif
                                                        @if($twitter !='')
                                                            <li><a target="_blank" href="{{$twitter}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="16" alt="Visit"></a></li>
                                                        @endif
                                                        @if($yt !='')
                                                            <li><a target="_blank" href="{{$yt}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="16" alt="Visit"></a></li>
                                                        @endif
                                                        @if($med !='')
                                                            <li><a target="_blank" href="#"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Medium.svg')}}" width="16" alt="Visit"></a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($type == 1)
                                        {{-- list view --}}
                                        <div class="blogpagex dynamic-courses-wrapper">
                                            <div class="col-12">
                                                <div class="instructor-inner">
                                                    <?php
                                                        $img = '';
                                                        $inst_url = $lvalue['slugable']['slug'];
                                                        $ext_url = $lvalue['ext_url'];
                                                        $fb = '';
                                                        $inst = '';
                                                        $twitter = '';
                                                        $med = '';
                                                        $pint = '';
                                                        $linkedIn = '';
                                                        $yt = '';
                                                        $name = $lvalue['title'] . ' ' . $lvalue['subtitle'];
                                                        $field1 = '';
                                                        $field2 =$lvalue['company'];

                                                        if(isset($lvalue['medias'])){
                                                            $img =  get_image($lvalue['medias'], 'instructors-testimonials');
                                                        }

                                                        if(isset($lvalue['header'])){
                                                            $field1 =  $lvalue['header'];
                                                        }

                                                        $socialMedia = json_decode($lvalue['social_media'],true);

                                                        if(isset($socialMedia['facebook'])){
                                                            $fb = $socialMedia['facebook'];
                                                        }

                                                        if(isset($socialMedia['twitter'])){
                                                            $twitter = $socialMedia['twitter'];
                                                        }

                                                        if(isset($socialMedia['instagram'])){
                                                            $inst = $socialMedia['instagram'];
                                                        }

                                                        if(isset($socialMedia['linkedin'])){
                                                            $linkedIn = $socialMedia['linkedin'];
                                                        }

                                                        if(isset($socialMedia['youtube'])){
                                                            $yt = $socialMedia['youtube'];
                                                        }
                                                    ?>

                                                    <div class="col-12 item mb-5">
                                                        <div class="row" style="width: 100%;">
                                                            <div class="col-md-3">
                                                                <div class='text-center blogpagex-blog-image d-flex'>
                                                                    <img style="align-self: center;" src="{{cdn($img)}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <h3><a href="{{env('NEW_PAGES_LINK') . '/' . $lvalue['slugable']['slug']}}">{{$name}}</a></h3>
                                                                <p>{{$field1}}, <a target="_blank" title="{{$field1}}" @if($ext_url!='') href="{{$ext_url}}"@endif>{{$field2}}</a>.</p>
                                                                <ul class="social-wrapper">
                                                                    @if($fb != '')
                                                                        <li class="pull-left me-1"><a target="_blank" href="{{$fb}}"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                                                                    @endif
                                                                    @if($inst !='')
                                                                        <li class="pull-left me-1"><a target="_blank" href="{{$inst}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="16" alt="Visit"></a></li>
                                                                    @endif
                                                                    @if($linkedIn !='')
                                                                        <li class="pull-left me-1"><a target="_blank" href="{{$linkedIn}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="16" alt="Visit"></a></li>
                                                                    @endif
                                                                    @if($pint !='')
                                                                        <li class="pull-left me-1"><a target="_blank" href="{{$pint}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Pinterest.svg')}}" width="16" alt="Visit"></a></li>
                                                                    @endif
                                                                    @if($twitter !='')
                                                                        <li class="pull-left me-1"><a target="_blank" href="{{$twitter}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="16" alt="Visit"></a></li>
                                                                    @endif
                                                                    @if($yt !='')
                                                                        <li class="pull-left me-1"><a target="_blank" href="{{$yt}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="16" alt="Visit"></a></li>
                                                                    @endif
                                                                    @if($med !='')
                                                                        <li class="pull-left me-1"><a target="_blank" href="#"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Medium.svg')}}" width="16" alt="Visit"></a></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
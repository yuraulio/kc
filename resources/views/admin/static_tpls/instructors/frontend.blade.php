@extends('theme.layouts.master')
@section('metas')

   {!! $page->metable->getMetas() !!}
@endsection
@section('content')
@include('theme.preview.preview_warning', ["id" => $page['id'], "type" => "page", "status" => $page['status']])

<main id="main-area" class="with-hero" role="main">
<section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
            <h1>Our Instructors</h1>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
<section class="section-course-tabs">
    <div class="content-wrapper">
        <div class="tabs-wrapper">
        <div class="tabs-content">
        <div id="instructors" class="tab-content-wrapper tab-blue-gradient active-tab our-instuctors">
                  <div class="container">
                     <div class="course-full-text">
                        @if(isset($instructors))

                        <div class="instructors-wrapper row row-flex row-flex-23">
                           @foreach($instructors as $lkey => $lvalue)
                           <div class="col-3 col-sm-6 col-xs-12">
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
                                       //$company = $lvalue['company'];

                                       //dd($lvalue);

                                       if(isset($lvalue['medias'])){
                                         $img =  get_image($lvalue['medias'], 'instructors-testimonials');
                                       }

                                       if(isset($lvalue['header'])){
                                         $field1 =  $lvalue['header'];
                                       }

                                       /*if(isset($lvalue['ext_url'])){
                                            $field2 = $lvalue['ext_url'];
                                            $field2 = str_replace ( "https://www.", "", $field2 );
                                            $field2 = str_replace ( "https://.", "", $field2 );
                                            $field2 = str_replace ( "http://www.", "", $field2 );  
                                            $field2 = str_replace ( "https:", "", $field2 );
                                            $field2 = str_replace ( "http:", "", $field2 );
                                            $field2 = str_replace ( "/", "", $field2 );
                                         
                                       }*/
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

                                       //dd($inst_url);
                                       ?>
                                    <div class="profile-img">
                                       <a href="{{$lvalue['slugable']['slug']}}"><img src="{{cdn($img)}}"  title="{{$name}}" alt="{{$name}}"></a>
                                    </div>
                                    <h3><a href="{{$lvalue['slugable']['slug']}}">{{$name}}</a></h3>
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
                                    <!-- /.instructor-inner -->
                                 </div>
                                 <!-- /.instructor-box -->
                              </div>
                              <!-- /.col-3.col-sm-12 -->
                           </div>
                           @endforeach
                           @endif
                           <!-- /.row.row-flex -->
                        </div>
                        <!-- /.course-full-text -->
                     </div>
                     <!-- /.container -->
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>
        </div>

        </div>
    </div>
</section>

</main>



@endsection

@section('scripts')

@stop

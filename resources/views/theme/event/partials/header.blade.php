<div id="section-header" class="tab-pane active">
   <div class="container" >
      <div class="row">
         @if($estatus == 0 || $estatus == 2)
         @include('theme.event.partials.social')
         @else
         <div class="social-helper"></div>
         @endif
         <div class="col-lg-7 col-md-7 col-sm-12 ">
            <?php
               $location = [];
               
                    	if (isset($content->categories) && !empty($content->categories)) :
                   foreach ($content->categories as $category) :
                          if ($category->depth != 0 && $category->parent_id == 9) {
                               $location=$category;
                          }
               
                   endforeach;
               endif;
               ?>
            <div class="post-content clearfix dpContentEntry" data-dp-content-id="{{ $content->id }}">
               <?php switch ($estatus) {
                  case 0: ?>
               {!! $content->body !!}
               <?php break;
                  case 1: ?>
               @if(isset($section_about_closed))
               <h3 class="single-post-head">{{ $section_about_closed->title }}</h3>
               {!! $section_about_closed->body !!}
               <p>Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>
               @else
               <h3 class="single-post-head">The event is closed</h3>
               <p>The best digital &amp; social media diploma with a long track record trusted by top executives, agencies, brands and corporations is closed.</p>
               <p>Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>
               @endif
               <?php break;
                  case 2: ?>
               {!! $content->body !!}
               <?php break;
                  case 3: ?>
               @if(isset($section_about_completed))
               <h3 class="single-post-head">{{ $section_about_completed->title }}</h3>
               {!! $section_about_completed->body !!}
               <p>Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>
               @else
               <h3 class="single-post-head">The event is completed</h3>
               <p style="display:none">The best digital &amp; social media diploma with a long track record trusted by top executives, agencies, brands and corporations is completed.</p>
               <p style="display:none">Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>
               @endif
               <?php break;
                  default: ?>
               {!! $content->body !!}
               <?php break;
                  } ?>
            </div>
            <div class='col-lg-12 col-md-12 col-sm-12 mar-top-35 height-100'>
               <div class='col-lg-5 col-md-5 col-sm-12 no-flex is-flex'>
               @include('theme.event.partials.syllabus-manager')
               </div>
               <div class='col-lg-5 col-md-5 col-sm-12 organisers no-flex is-flex'>
               @include('theme.event.partials.organisers')
               </div>
            </div>
         </div>
         <div class="col-lg-4 col-md-4 col col-sm-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 text-center summary-icons-top">
            <?php if (isset($content['c_fields']['simple_text'][2]) && $content['c_fields']['simple_text'][2]['value'] != '') : ?>
            <div class="col-lg-6  col-md-6 col-sm-12 col-xs-12">
               <div class="sum-icon">
                  <img class="summary_ic" src="theme/assets/img/new_icons/duration.svg" alt="Hours" title="Hours" />
                  <p>{{ $content['c_fields']['simple_text'][2]['value'] }}</br>
                     {{ $content['c_fields']['simple_text'][3]['value'] }}
                  </p>
               </div>
            </div>
            <?php endif; ?>
            <?php if (isset($content['c_fields']['simple_text'][8]) && $content['c_fields']['simple_text'][8]['value'] != '') : ?>
            <div class="col-lg-6  col-md-6 col-sm-12 col-xs-12" >
               <div class="sum-icon">
                  <img class="summary_ic" src="theme/assets/img/new_icons/hour.svg" alt="Videos" title="Videos" />
                  <p>{{ $content['c_fields']['simple_text'][8]['value'] }}</br>
                     {{ $content['c_fields']['simple_text'][9]['value'] }}
                  </p>
               </div>
            </div>
            <?php endif; ?>
            <?php /*if (isset($content['c_fields']['simple_text'][4]) && $content['c_fields']['simple_text'][4]['value'] != '') : ?>
            <div class="col-lg-5  col-md-5 col-sm-5 col-xs-5" style="margin-left: 55px;">
               <div class="sum-icon">
                  <img class="summary_ic" src="theme/assets/img/new_icons/school.svg" alt="EFQ Level 5" title="EFQ Level 5" />
                  <p>{!! $content['c_fields']['simple_text'][4]['value'] !!}</br>
                     {{ $content['c_fields']['simple_text'][5]['value'] }}
                  </p>
               </div>
            </div>
            <?php endif; */?>
            <?php if (isset($content['c_fields']['simple_text'][0]) && $content['c_fields']['simple_text'][0]['value'] != '') : ?>
            <div class="col-lg-6  col-md-6 col-sm-12 col-xs-12">
               <div class="sum-icon">
                  <img class="summary_ic" src="theme/assets/img/new_icons/event.svg" alt="Date" title="Date" />
                  <p>{{ $content['c_fields']['simple_text'][0]['value'] }}</br>
                     {{ $content['c_fields']['simple_text'][1]['value'] }}
                  </p>
               </div>
            </div>
            <?php endif; ?>
            <?php if (isset($content['c_fields']['simple_text'][4]) && $content['c_fields']['simple_text'][4]['value'] != '') : ?>
            <div class="col-lg-6  col-md-6 col-sm-12 col-xs-12">
               <div class="sum-icon">
                  <img class="summary_ic" src="theme/assets/img/new_icons/school.svg" alt="User Level" title="User Level" />
                  <p>{{ $content['c_fields']['simple_text'][4]['value'] }}</br>
                     {{ $content['c_fields']['simple_text'][5]['value'] }}
                  </p>
               </div>
            </div>
            <?php endif; ?>
            <?php if (isset($content['c_fields']['simple_text'][6]) && $content['c_fields']['simple_text'][6]['value'] != '') : ?>
            <div class="col-lg-6  col-md-6 col-sm-12 col-xs-12" >
               <div class="sum-icon p">
                  <img class="summary_ic" src="theme/assets/img/new_icons/language.svg" alt="Language" title="Language" />
                  <p>{{ $content['c_fields']['simple_text'][6]['value'] }}</br>
                     {{ $content['c_fields']['simple_text'][7]['value'] }}
                  </p>
               </div>
            </div>
            <?php endif; ?>
            <?php if (isset($content['c_fields']['simple_text'][10]) && $content['c_fields']['simple_text'][10]['value'] != '') : ?>
            <div class="col-lg-6  col-md-6 col-sm-12 col-xs-12">
               <div class="sum-icon">
                  <img class="summary_ic" src="theme/assets/img/new_icons/event_available.svg" alt="Days" title="Days" />
                  <p>{{ $content['c_fields']['simple_text'][10]['value'] }}</br>
                     {{ $content['c_fields']['simple_text'][11]['value'] }}
                  </p>
               </div>
            </div>
            <?php endif; ?>
            <?php /*if (isset($content['c_fields']['simple_text'][8]) && $content['c_fields']['simple_text'][8]['value'] != '') : ?>
            <div class="col-lg-5  col-md-5 col-sm-5 col-xs-5" style="margin-left: 22px;">
               <div class="sum-icon">
                  <img class="summary_ic" src="theme/assets/img/new_icons/cloud_download.svg" alt="Download" title="Download" />
                  <p>{{ $content['c_fields']['simple_text'][8]['value'] }}</br>
                     {{ $content['c_fields']['simple_text'][9]['value'] }}
                  </p>
               </div>
            </div>
            <?php endif; */?>
            <div class="col-lg-12 col-md-12 col-sm-12">
               @if(isset($calendar_links_disabled))
               <div class="dp-dropdown">
                  <button onclick="myFunction()" class="dropbtn"><i class="fa fa-calendar"></i> Add to calendar</button>
                  <div id="myDropdown" class="dp-dropdown-content">
                     <?php $imid = 1; ?>
                     @foreach($calendar_links as $ckey => $clink)
                     <a target="_blank" title="Add to calendar" href="{{$clink}}"><img src="theme/assets/img/calicons/{{$imid}}.svg" /> {{$ckey}}
                     </a>
                     <?php $imid++; ?>
                     @endforeach
                  </div>
               </div>
               @endif
            </div>
         </div>
      </div>

      @include('theme.event.partials.fullvideo')

   </div>
</div>
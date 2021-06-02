<div id="overview" class="tab-pane active ">
   <div class="container" id="diploma">
      <div class="row">
         <div class='tab-background summary'>
      
      
            <div class='row'>
               <div class='col-lg-7 overview-text'>
            {{-- @include('theme.event.partials.social')--}}
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
                  <div class="post-content clearfix dpContentEntry  overview-text" data-dp-content-id="{{ $content->id }}">
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
               </div>
               <div class='col-lg-1 col-lg-offset-1 icons'>
                  <?php if (isset($content['c_fields']['simple_text'][2]) && $content['c_fields']['simple_text'][2]['value'] != '') : ?>
                  <div class="sum-icon">
                     <img class="summary_ic" src="theme/assets/img/new_icons/duration.svg" alt="Hours" title="Hours" />
                     <p>{{ $content['c_fields']['simple_text'][2]['value'] }}</br>
                        {{ $content['c_fields']['simple_text'][3]['value'] }}
                     </p>
                  </div>
                  <?php endif; ?>
                  <?php if (isset($content['c_fields']['simple_text'][0]) && $content['c_fields']['simple_text'][0]['value'] != '') : ?>
                  <div class="sum-icon">
                     <img class="summary_ic" src="theme/assets/img/new_icons/event.svg" alt="Date" title="Date" />
                     <p>{{ $content['c_fields']['simple_text'][0]['value'] }}</br>
                        {{ $content['c_fields']['simple_text'][1]['value'] }}
                     </p>
                  </div>
                  <?php endif; ?>
                  <?php if (isset($content['c_fields']['simple_text'][4]) && $content['c_fields']['simple_text'][4]['value'] != '') : ?>
                  <div class="sum-icon">
                     <img class="summary_ic" src="theme/assets/img/new_icons/school.svg" alt="User Level" title="User Level" />
                     <p>{{ $content['c_fields']['simple_text'][4]['value'] }}</br>
                        {{ $content['c_fields']['simple_text'][5]['value'] }}
                     </p>
                  </div>
                  <?php endif; ?>
                  <?php if (isset($content['c_fields']['simple_text'][10]) && $content['c_fields']['simple_text'][10]['value'] != '') : ?>
                  <div class="sum-icon">
                     <img class="summary_ic" src="theme/assets/img/new_icons/event_available.svg" alt="Days" title="Days" />
                     <p>{{ $content['c_fields']['simple_text'][10]['value'] }}</br>
                        {{ $content['c_fields']['simple_text'][11]['value'] }}
                     </p>
                  </div>
                  <?php endif; ?>
                  <?php if (isset($content['c_fields']['simple_text'][6]) && $content['c_fields']['simple_text'][6]['value'] != '') : ?>
                  <div class="sum-icon p">
                     <img class="summary_ic" src="theme/assets/img/new_icons/language.svg" alt="Language" title="Language" />
                     <p>{{ $content['c_fields']['simple_text'][6]['value'] }}</br>
                        {{ $content['c_fields']['simple_text'][7]['value'] }}
                     </p>
                  </div>
                  <?php endif; ?>
                  <?php if (isset($content['c_fields']['simple_text'][8]) && $content['c_fields']['simple_text'][8]['value'] != '') : ?>
                  <div class="sum-icon">
                     <img class="summary_ic" src="theme/assets/img/new_icons/hour.svg" alt="Videos" title="Videos" />
                     <p>{{ $content['c_fields']['simple_text'][8]['value'] }}</br>
                        {{ $content['c_fields']['simple_text'][9]['value'] }}
                     </p>
                  </div>
                  <?php endif; ?>
                  <div>
                  </div>

                  @include('theme.event.elearning.syllabus-manager')

               </div>
            </div>
         </div>
      </div>
   </div>
</div>

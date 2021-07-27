 <!-- TOPICS -->
 @if(isset($section_topics))


	            <div class="col-lg-12 col-md-12 col-sm-12 topics">
	            	{{-- <div class="accordion-option">
	                    <a title="expand/collapse all" href="javascript:void(0)" class="toggle-accordion" accordion-id="#topicsaccordion">+</a>
	                </div> --}}
	                <div class="clearfix"></div>
	        		<div class="panel-group panel-shadow" id="topicsaccordion" role="tablist" aria-multiselectable="true">
			            @if(isset($topics))
			                @foreach($topics as $key => $value)
								
			                    	<div class="panel panel-default">
			                            <div class="panel-heading" role="tab" id="theading{{ $key }}">
			                                <h3 class="panel-title">
			                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#topicsaccordion" href="#tcollapse{{ $key }}" title="Collapse" aria-expanded="false" aria-controls="tcollapse{{ $key }}">
			                                    	{{-- TOPIC TITLE HERE --}}
													{!! $value->name !!}
			                                    </a>
			                                </h3>
			                            </div>
			                            <div id="tcollapse{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="theading{{ $key }}">
			                                <div class="panel-body" id="topics">
			                                	{{--<div class="topic-desc">{!! $value->description !!}</div>--}}
			                                    {{-- TOPIC LESSONS HERE --}}
			                        			<ul class="lesson-list">
						                         @if(isset($etax))
						                         	<?php $lastid = []; ?>
						                            @foreach($etax as $lkey => $lvalue)
													
						                            	@foreach($lvalue['lesson']['categories'] as $catkey => $catvalue)
						                            		@if($catvalue['parent_id'] == $blockcat)
						                            			<?php $thislessonintopic = $catvalue['id']; ?>
						                            		@endif
						                            	@endforeach
						                            	@if($value->id == $thislessonintopic)
						                            	@if(!in_array($lvalue['lesson_id'], $lastid))
							                            <li>
									                        <div class="lesson-list-main">
									                            
								                                <div class="lesson-list-title">
																<h4>{!! $lvalue['lesson']['title'] !!}</h4>
								                                    {{-- $tvalue['content']['title'] --}}
																</div>
																
																<div class="lesson-list-quickbar">

									                            	 @if($content->view_tpl == 'elearning_event' || $content->view_tpl == 'elearning_greek')
									                            	   @if(isset($lvalue['lesson']['vimeo_video']) && ($lvalue['lesson']['vimeo_video'] != "") )
										                            	   @if($is_event_paid==0 )
										                            	 	<img class="vimeo_locked" src="{{ URL::to('/assets/colorbox/images/locked_video.png') }}" alt="Enroll to unlock video"  title="Enroll to unlock video" />
										                            	   @else
										                            	    <a class='vimeo' href="{{ $lvalue['lesson']['vimeo_video'] }}" title="{{ $lvalue['lesson']['title'] }} Video"><img class="vimeo_unlocked" src="{{ URL::to('/assets/colorbox/images/vimeo_play_button.png') }}" alt="Play Video" /></a>
										                            	    <script>
										                            	    	$(document).ready(function(){
																					$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
																				});
										                            	    </script>
										                            	   @endif
										                               @endif
																	 @else
																	 

																	 @if (isset($lvalue['lesson']['custom_fields'][2]))

																		<?php $cfield = Config::get('dpoptions.custom_field.dropdown_select_type.settings'); ?>
																			@if(isset($cfield) && isset($cfield['alltypes']) && !empty($cfield['alltypes']))
																				<?php
																				$type_index = $lvalue['lesson']['custom_fields'][2]['value'];
																				
																				$type = $cfield['alltypes'][$type_index];
																				//echo $type; ?>
																			@else
																			<?php $type = false;?>
																			
																			@endif

	
																		
																		@endif


									                            		@if($lvalue['timestarts'] != '')
							                            				<?php
								                            				$timestamp = strtotime($lvalue['timestarts']);

								                            				$eldate = date('l d F Y', $timestamp);
																			$eltime = date('H:i', $timestamp); ?>

							                            				@if($type) <span style="color:#439efa; font-weight:bold; padding: 0;text-transform: uppercase;">{{$type}}</span> <span class="hidden-xs">|</span>	@endif<img src="theme/assets/img/new_icons/topics/calendar.svg" alt="Date" title="Date" />{{ $eldate }}<span class="hidden-xs">|</span> <img src="theme/assets/img/new_icons/topics/time.svg" alt="Time" title="Time" />{{ $eltime }}&nbsp;&nbsp;({{ $lvalue['duration'] }})&nbsp; <span class="hidden-xs">| </span><span class="hidden-lg hidden-md hidden-sm"><br /></span><img src="theme/assets/img/new_icons/topics/pin.svg" alt="Location" title="Location" />{{ $lvalue['room'] }}
							                            				@else
							                            					TBC
							                            				@endif
							                            			@endif

							                            					

							                            					{{-- break --}}
							                            				{{-- endif --}}
							                            			{{-- endforeach
							                            			endif --}}

																</div>
																
																<div class="lesson-list-inst">
							                            			@foreach($etax as $seclkey => $seclvalue)
							                            				@if($seclvalue['lesson_id'] == $lvalue['lesson']['id'])

							                                                @if(!empty($seclvalue['instructor']['featured']) && isset($seclvalue['instructor']['featured'][0]) && isset($seclvalue['instructor']['featured'][0]['media']) && !empty($seclvalue['instructor']['featured'][0]['media']))
														                    	<?php $media = $seclvalue['instructor']['featured'][0]['media']; ?>
														                    	<div class="lesson-list-inst-image">
														                    		{{-- <a href="/{{ $seclvalue['instructor']['slug'] }}" title="{{ $seclvalue['instructor']['title'] }} {{ $seclvalue['instructor']['subtitle'] }}"> --}}
																                    <a class="intructor-alt" data-title="{{ $seclvalue['instructor']['title'] }} {{ $seclvalue['instructor']['subtitle'] }}" href="/{{ $seclvalue['instructor']['slug'] }}">	<img  class="img-responsive"  alt="{{ $seclvalue['instructor']['title'] }} {{ $seclvalue['instructor']['subtitle'] }}" src="{{ $frontHelp->pImg($seclvalue['instructor'], 'users') }}" /></a>
																                   {{--  </a> --}}
														                    	</div>
														                    @else
														                    @endif
							                            				@endif
							                            			@endforeach
							                                </div>
								                            </div>

							                            </li>
							                            @endif
							                            @endif

							                           <?php $lastid[] =  $lvalue['lesson_id']; ?>

						                        	@endforeach
						                        @endif
						                        </div>
			                                </div>
			                            </div><!-- END PANEL -->
				                @endforeach
				            @endif
	            		</div>
	            	</div><!-- END COL-12 -->


@endif
 <!-- TOPICS END -->

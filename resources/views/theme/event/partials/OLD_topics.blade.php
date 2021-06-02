 <!-- TOPICS -->
<div id="section-topics" class="event-section">
    <div class="container">
		<div class="row">
	        <div class="col-lg-12 col-md-12 col-sm-12">
	        	@if(isset($event_blocks) && isset($event_blocks[0]))
        			<h2>{{ $event_blocks[0]->title }}</h2>
	                	{!! $event_blocks[0]->body !!}

				@else
    			<h2>Course's topics</h2>
    			@endif
	        
	        <div class="row">
	            <div class="col-lg-12 col-md-12 col-sm-12">
	            	<!-- <div class="accordion-option">
	                    <a title="expand/collapse all" href="javascript:void(0)" class="toggle-accordion" accordion-id="#topicsaccordion">+</a>
	                </div> -->
	                <div class="clearfix"></div>
	        		<div class="panel-group" id="topicsaccordion" role="tablist" aria-multiselectable="true">
			            @if(isset($topics))
			                @foreach($topics as $key => $value)

			                    	<div class="panel panel-default">
			                            <div class="panel-heading" role="tab" id="theading{{ $key }}">
			                                <h4 class="panel-title">
			                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#topicsaccordion" href="#tcollapse{{ $key }}" aria-expanded="false" aria-controls="tcollapse{{ $key }}">
			                                    	<!-- TOPIC TITLE HERE -->
			                                        {{ $value->name }}
			                                    </a>
			                                </h4>
			                            </div>
			                            <div id="tcollapse{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="theading{{ $key }}">
			                                <div class="panel-body">
			                                	<div class="topic-desc">{{ $value->description }}</div>
			                                    <!-- TOPIC LESSONS HERE -->
			                                    <?php

			                                    //dd($value->id);
			                                    if ($value->id) {
			                                    	$data['lessons'] = PostRider\CategoryContent::themePartialSection($value->id, 20, [
			                                    'type' => 0,
			                                    'ctype' => 34,
			                                    ])->get()->toArray();

			                                    }
			                                    else {
			                                    	$data['lessons'] = [];
			                                    }



			                        			?>
			                        			<ul class="lesson-list">
						                         @foreach($data['lessons'] as $tkey => $tvalue)
						                            <li>
								                        <div class="lesson-list-main">
								                            <div class="lesson-list-quickbar">
								                            	@if(isset($etax))
						                            			@foreach($etax as $lkey => $lvalue)
						                            				@if($lvalue['lesson_id'] == $tvalue['content']['id'])
						                            				<?php
							                            				$timestamp = strtotime($lvalue['timestarts']);

							                            				$eldate = date('d-m-Y', $timestamp);
																		$eltime = date('H:i', $timestamp); ?>
						                            					<img src="theme/assets/img/pin.svg" alt="Location" />{{ $lvalue['room'] }} &nbsp; | <img src="theme/assets/img/calendar.svg" alt="Date" />{{ $eldate }} &nbsp; | <img src="theme/assets/img/clock.svg" alt="Time" /> {{ $eltime }}
						                            					@if (isset($lvalue['lesson']['custom_fields'][0]) && $lvalue['lesson']['custom_fields'][0]['value'] == '1') &nbsp; | &nbsp;<span class="exclusive">Exclusive</span>@endif

						                            					@if (isset($lvalue['lesson']['custom_fields'][1]))

						                            						<?php switch ($lvalue['lesson']['custom_fields'][1]['value']) {
						                            							case 1:
						                            								$level = 'Beginner';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 2:
						                            								$level = 'Intermediate';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 3:
						                            								$level = 'Advanced';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;

						                            							default:
						                            								$level = '';
						                            								break;
						                            						} ?>

						                            					<span class="handson">{{ $level }}</span>
						                            					@endif
						                            					@if (isset($lvalue['lesson']['custom_fields'][2]))

						                            						<?php switch ($lvalue['lesson']['custom_fields'][2]['value']) {
						                            							case 1:
						                            								$type = 'Hands-on';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 2:
						                            								$type = 'Lab';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 3:
						                            								$type = 'Team exersise';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 4:
						                            								$type = 'Live demo';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 5:
						                            								$type = 'Final Exam';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 6:
						                            								$type = 'Assessment';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 7:
						                            								$type = 'Lecture';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;
						                            							case 8:
						                            								$type = 'Networking event';
						                            								echo '&nbsp; | &nbsp;';
						                            								break;

						                            							default:
						                            								$type = '';
						                            								break;
						                            						} ?>

						                            					<span class="lesstype">{{ $type }}</span>
						                            					@endif

						                            					@break
						                            				@endif
						                            			@endforeach
						                            			@endif

							                                </div>
							                                <div class="lesson-list-title">
							                                   {{ $tvalue['content']['title'] }}
							                                </div>
							                            </div>

						                                <div class="lesson-list-inst">
						                                    @if(isset($etax))
						                            			@foreach($etax as $lkey => $lvalue)
						                            				@if($lvalue['lesson_id'] == $tvalue['content']['id'])

						                                                @if (!empty($lvalue['instructor']['featured']) && isset($lvalue['instructor']['featured'][0]) &&isset($lvalue['instructor']['featured'][0]['media']) && !empty($lvalue['instructor']['featured'][0]['media']))
													                    	<?php $media = $lvalue['instructor']['featured'][0]['media']; ?>
													                    	<div class="lesson-list-inst-image">
													                    		<a href="/{{ $lvalue['instructor']['slug'] }}" title="{{ $lvalue['instructor']['title'] }} {{ $lvalue['instructor']['subtitle'] }}">
															                    	<img  class="img-responsive" alt="{{ $lvalue['instructor']['title'] }} {{ $lvalue['instructor']['subtitle'] }}" src="{{ $frontHelp->pImg($lvalue['instructor'], 'users') }}" />
															                    </a>
													                    	</div>
													                    @else

													                    @endif

						                            				@endif
						                            			@endforeach
						                            		@endif
						                                </div>
						                            </li>
						                        @endforeach
						                        </div>
			                                </div>
			                            </div><!-- END PANEL -->
				                @endforeach
				            @endif
	            		</div>
	            	</div><!-- END COL-12 -->
	            </div><!-- END ROW -->
	        </div>
        </div>
    </div>
</div>
 <!-- TOPICS END -->

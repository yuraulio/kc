 <!-- INSTRUCTORS -->
 @if(isset($section_challenge_debate))
<div id="section-challenge-debate" class="event-section">
    <div class="container">
    	<div class="row">
    		<div class="col-lg-12">

        			<h2 class="section-head">{{ $section_challenge_debate->title }}
        				</h2>
	                	{!! $section_challenge_debate->body !!}
	            <div class="row">
	                <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-5 col-sm-offset-0 text-center">

	                	@if(isset($team1))
	                		<h3>{{ $team1->team_name }}</h3>
	                		<h4>Team Leader</h4>


	                		<div class="inst-cell">
		    					<div class="event-instruct-image">
		                            <a href="{{ $frontHelp->pSlug($team1->leader) }}" title="{{ $frontHelp->pField($team1->leader, 'title') }}">
		                            <a href="{{ $team1->leader->slug }}" title="{{ $frontHelp->pField($team1->leader, 'title') }}">
		                            @if(isset($team1->leader['featured'][0]['media']))
		                            	<img alt="{{ $frontHelp->pField($team1->leader, 'title') }} {{ $team1->leader['subtitle'] }}" src="{{ $frontHelp->pImg($team1->leader, 'instructors-testimonials') }}" class="img-responsive hvr-grow" />
		                            @else
		                            	<img alt="{{ $frontHelp->pField($team1->leader, 'title') }} {{ $team1->leader['subtitle'] }}" src="assets/img/no-featured-60.jpg"  class="img-responsive hvr-grow"/>
		                        	@endif
		                    		</a>
		                        </div>
		                        <div class="inst-text">
			                    	<span class="inst-name">{{ $team1->leader['title'] }} {{ $team1->leader['subtitle'] }}</span><br />
			                    	@if(isset($team1->leader['header']))
			                    		<?php $thecompany1 = ''; ?>
				                    	@if(isset($team1->leader['customFields']))
			                    			@foreach($team1->leader['customFields'] as $key => $value)
			                    				@if($value['value'] != '')
			                    					@if($value['name'] == 'simple_text' && $value['priority'] == 1)
			                    					<?php $thecompany1 = ', '.$value['value'];  break; ?>
			                    					@endif
			                    				@endif
		                    				@endforeach
		                    			@endif
			                    	<span class="inst-info">{{ $team2->leader['header'] }}{{ $thecompany1 }}</span>
			                    	@endif
			                    </div>

		                    	<div class="inst-social">
		                    		<?php //dd($team1->leader['customFields']); ?>
		                    		@if(isset($team1->leader['customFields']))
		                    		@foreach($team1->leader['customFields'] as $key => $value)
		                    			@if($value['value'] != '')
		                    			<?php
		                    				switch ($value['priority']) {
		                    					case 2: ?>
		                    						<a target="_blank" title="Find me on facebook" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/facebook.svg" /></a>
		                    					<?php

		                    						break;
		                    					case 3:
		                    					?>
		                    						<a target="_blank" title="Find me on twitter" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/twitter.svg" /></a>

		                    					<?php
		                    					break;
		                    					case 4:
		                    					?>
		                    						 <a target="_blank" title="Find me on instagram" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/instagram.svg" /></a>

		                    					<?php
		                    					break;
		                    					case 5:
		                    					?>
		                    						 <a target="_blank" title="Find me on linkedin" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/linkedin.svg" /></a>

		                    					<?php
		                    					break;
		                    					case 6:
		                    					?>
		                    						<a target="_blank" title="Find me on youtube" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/youtube.svg" /></a>

		                    					<?php
		                    					break;

		                    					default:
		                    						# code...
		                    						break;
		                    				}


		                    			?>

		                    			@endif

		                    		@endforeach
		                    		@endif

		                    	</div>
							</div>



	                	@endif
	                </div>
	                <div class="col-lg-4 col-lg-offset-2 col-md-4 col-md-offset-2 col-sm-5 col-sm-offset-2 text-center">
	                	@if(isset($team2))
	                		<h3>{{ $team2->team_name }}</h3>
	                		<h4>Team Leader</h4>
	                		<div class="inst-cell">
		    					<div class="event-instruct-image">
		                            <a href="{{ $frontHelp->pSlug($team2->leader) }}" title="{{ $frontHelp->pField($team2->leader, 'title') }}">
		                            <a href="{{ $team2->leader->slug }}" title="{{ $frontHelp->pField($team2->leader, 'title') }}">
		                            @if(isset($team2->leader['featured'][0]['media']))
		                            	<img alt="{{ $frontHelp->pField($team2->leader, 'title') }} {{ $team2->leader['subtitle'] }}" src="{{ $frontHelp->pImg($team2->leader, 'instructors-testimonials') }}" class="img-responsive hvr-grow" />
		                            @else
		                            	<img alt="{{ $frontHelp->pField($team2->leader, 'title') }} {{ $team2->leader['subtitle'] }}" src="assets/img/no-featured-60.jpg"  class="img-responsive hvr-grow"/>
		                        	@endif
		                    		</a>
		                        </div>
		                        <div class="inst-text">
			                    	<span class="inst-name">{{ $team2->leader['title'] }} {{ $team2->leader['subtitle'] }}</span><br />
			                    	@if(isset($team2->leader['header']))
			                    		<?php $thecompany2 = ''; ?>
				                    	@if(isset($team2->leader['customFields']))
			                    			@foreach($team2->leader['customFields'] as $key => $value)
			                    				@if($value['value'] != '')
			                    					@if($value['name'] == 'simple_text' && $value['priority'] == 1)
			                    					<?php $thecompany2 = ', '.$value['value'];  break; ?>
			                    					@endif
			                    				@endif

		                    				@endforeach
		                    			@endif
			                    	<span class="inst-info">{{ $team2->leader['header'] }}{{ $thecompany2 }}</span>
			                    	@endif
			                    </div>

		                    	<div class="inst-social">
		                    		<?php //dd($team1->leader['customFields']); ?>
		                    		@if(isset($team2->leader['customFields']))
		                    		@foreach($team2->leader['customFields'] as $key => $value)
		                    			@if($value['value'] != '')
		                    			<?php
		                    				switch ($value['priority']) {
		                    					case 2: ?>
		                    						<a target="_blank" title="Find me on facebook" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/facebook.svg" /></a>
		                    					<?php

		                    						break;
		                    					case 3:
		                    					?>
		                    						<a target="_blank" title="Find me on twitter" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/twitter.svg" /></a>

		                    					<?php
		                    					break;
		                    					case 4:
		                    					?>
		                    						 <a target="_blank" title="Find me on instagram" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/instagram.svg" /></a>

		                    					<?php
		                    					break;
		                    					case 5:
		                    					?>
		                    						 <a target="_blank" title="Find me on linkedin" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/linkedin.svg" /></a>

		                    					<?php
		                    					break;
		                    					case 6:
		                    					?>
		                    						<a target="_blank" title="Find me on youtube" href="{{ $value['value'] }}"><img class="social_ins" src="theme/assets/img/youtube.svg" /></a>

		                    					<?php
		                    					break;

		                    					default:
		                    						# code...
		                    						break;
		                    				}


		                    			?>

		                    			@endif

		                    		@endforeach
		                    		@endif

		                    	</div>
							</div>
	                	@endif
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-xs-6 text-center">
	                	@if(isset($team1))
	                		@if(isset($inslist1) && !empty($inslist1))
	                		<h3>{{ $team1->team_name }} members</h3>

	                		@foreach ($inslist1 as $key => $value)
	                			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
	                				<div class="member-tile-icon">
	                			 		{{ $value->first_name }} {{ $value->last_name }}
	                			 	</div>
	                			</div>
	                		@endforeach

	                		@else
	                			<h3>{{ $team1->team_name }} members</h3>
	                			@for($i=0; $i < 4; $i++)
	                				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
	                					<a href="{{ Request::url() }}#section-seats">
		                				<div class="member-tile-icon">
		                			 		Be a team member
		                			 	</div>
		                			 	</a>
		                			</div>
	                			@endfor
	                		@endif

	                	@endif
	                </div>
	                <div class="col-xs-6 text-center">
						@if(isset($team2))
							@if(isset($inslist2) && !empty($inslist2))
		                		<h3>{{ $team2->team_name }} members</h3>

		                		@foreach ($inslist2 as $key => $value)
		                			 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
		                			 	<div class="member-tile-icon">
		                			 		{{ $value->first_name }} {{ $value->last_name }}
		                			 	</div>
		                			</div>
		                		@endforeach
	                		@else
	                			<h3>{{ $team2->team_name }} members</h3>
	                			@for($i=0; $i < 4; $i++)
	                				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
		                				<a href="{{ Request::url() }}#section-seats"><div class="member-tile-icon">
		                			 		Be a team member
		                			 	</div>
		                			 	</a>
		                			</div>
	                			@endfor
	                		@endif
	                	@endif
	                </div>
	            </div>

    		</div>
    	</div>
    </div>
</div>
@endif
<!-- INSTRUCTORS END -->

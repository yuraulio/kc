<div class="home-filters hidden">
    <div class="container">
    	<div class="rowme">
        <div class="flex-container">
            <div class="flex-item">
                <h2>Upcoming Events</h2>
                <?php if(Session::has('scopeone')){
                	$fone = Session::get('scopeone');
                }
                else { $fone = 0; }
                if(Session::has('scopetwo')){
                	$ftwo = Session::get('scopetwo');
                }
                else { $ftwo = 0; }

                if(Session::has('scopethree')){
                  $fthree = Session::get('scopethree');
                }
                else { $fthree = 0; }
                //echo '<span class="hidden" id="filt1">'.$fone.'</span><span class="hidden" id="filt2">'.$ftwo.'</span>';
               	$sel1 = ''; $sel2 = ''; $sel3 = ''; ?>

                 @if (!empty($filter_type))
                 	@foreach ($filter_type as $key => $row)
                        @if($row->id == $fone) <?php $sel1 = $row->name; ?> @endif
                    @endforeach
                @endif

                @if (!empty($filter_location))
                 	@foreach ($filter_location as $key => $row)
                        @if($row->id == $ftwo) <?php $sel2 = $row->name; ?> @endif
                    @endforeach
                @endif

                @if (!empty($filter_topic))
                  @foreach ($filter_topic as $key => $row)
                        @if($row->id == $fthree) <?php $sel3 = $row->name; ?> @endif
                    @endforeach
                @endif
            </div>

            <div class="flex-item bl2px">
                <span class="dropdown">
                  <span class="dropdown-toggle" data-toggle="dropdown">Type: <span class="fselect">{{ $sel1 }}</span> <b class="caret"></b></span>
                  <ul class="dropdown-menu">
                    @if (!empty($filter_type))
                      <li><a class="cat-filter" data-id="0" data-scope="1" title="Clear filter" href="#">All types</a></li>
                        @foreach ($filter_type as $key => $row)
                        	@if(count($row->posts) > 0)
	                          <li>
	                              <a class="cat-filter" data-id="{{ $row->id }}" data-scope="1" title="{!! $row->name !!}" href="#">{!! $row->name !!}</a>
	                          </li>
                          	@endif
                        @endforeach
                    @endif
                  </ul>
                </span>
            </div>

            <div class="flex-item bl2px">
                <span class="dropdown">
                  <span class="dropdown-toggle" data-toggle="dropdown">Topic: <span class="fselect">{{ $sel3 }}</span> <b class="caret"></b></span>
                  <ul class="dropdown-menu">
                  	@if (!empty($filter_topic))
                  		<li><a class="cat-filter" data-id="0" data-scope="3" title="Clear filter" href="#">All topics</a></li>
                        @foreach ($filter_topic as $key => $row)
                        	@if(count($row->posts) > 0)
                        	<li>
                            	<a class="cat-filter" data-id="{{ $row->id }}" data-scope="3" title="{!! $row->name !!}" href="#">{!! $row->name !!}</a>
	                        </li>
	                        @endif
                        @endforeach
                    @endif
                  </ul>
                </span>
            </div>
            <div class="flex-item bl2px">
                <span class="dropdown">
                  <span class="dropdown-toggle" data-toggle="dropdown">Place: <span class="fselect">{{ $sel2 }}</span> <b class="caret"></b></span>
                  <ul class="dropdown-menu">
                    @if (!empty($filter_location))
                    	<li><a class="cat-filter" data-id="0" data-scope="2" title="Clear filter" href="#">All cities</a></li>
                        @foreach ($filter_location as $key => $row)
                        	@if(count($row->posts) > 0)
                        	<li>
                            	<a class="cat-filter" data-id="{{ $row->id }}" data-scope="2" title="{!! $row->name !!}" href="#">{!! $row->name !!}</a>
	                        </li>
	                        @endif
                        @endforeach
                    @endif
                  </ul>
                </span>
            </div>

            <div class="flex-item bl2px">
            	<div class="view-options">
                <span class="listgrid hidden-md hidden-sm">View as:</span> <span id="grid"><img class="itools" src="theme/assets/img/view_buttons/grid.svg" alt="Grid" title="Grid" /></span><span id="list"><img class="itools" src="theme/assets/img/view_buttons/list.svg" alt="List" title="List" /></span><!-- <span id="bydate"><img class="itools" src="theme/assets/img/view_buttons/calendar_view.svg" alt="Calendar" /></span> -->
                <span class="hidden" id="vhold">1</span>
            	</div>
            </div>
        </div>
    </div>
    </div>
</div>
<div id="loadingDivF"><img class="img-responsive" src="theme/assets/img/ajax-loader-blue.gif" alt="Loader" title="Loader" /></div>

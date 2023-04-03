@if(isset($newlayoutExamsEvent[$content->id]))
                              <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                                 @foreach($newlayoutExamsEvent[$content->id] as $p)

                                 @foreach($p['exams'] as $pe)
                                 <div class="item">
                                    <div class="left">
                                       <h2 class="item-title"><a href="{{$p['slug']}}">{{$p['title']}}</a></h2>
                                       <div class="bottom">
                                          <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/marker.svg')}}" alt=""><a href="{{$p['location']['slug']}}">{{$p['location']['city']}}</a></div>
                                          <div class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="">{{$p['date']}}</div>
                                          @if($p['hours'])
                                          <div class="expire-date"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$p['hours']}}h</div>
                                          @endif
                                       </div>
                                    </div>
                                    <div class="right right--no-price">
                                       <!-- Feedback 8-12 changed -->
                                       @if($pe->exstatus == 1)
                                       <a target="_blank" href="{{ url('student-summary/' . $pe->id . '/' . Sentinel::getUser()->id) }}?s=1" title="{{$p['title']}}" class="btn btn--secondary btn--md">VIEW RESULT</a>
                                       @elseif($pe->islive == 1)
                                       <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$pe->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['title']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                                       @elseif($pe->isupcom == 1)
                                       <a  title="{{$p['title'] }}" class="btn btn--secondary btn--md">{{ date('F j, Y', strtotime($pe->publish_time)) }}</a>
                                       @endif
                                    </div>
                                 </div>
                                 <!-- ./item -->
                                 @endforeach
                                 @endforeach
                              </div>
                              <!-- ./dynamic-courses-wrapper -->
                              @endif

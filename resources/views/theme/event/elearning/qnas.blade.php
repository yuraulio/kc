<!-- QnAs -->
 
<div id="section-qnas" class="tab-pane">
    <div class="container" >
    	<div class="row">
    		<div class="col-lg-12">    			
        		<h2>{!! $section_qnas->title !!}</h2>
        	{{--	<span class="section-subt">{!! $section_qnas->body !!}</span>--}}
				<!-- <h2>Questions and answers</h2>
	            <p class="section-p">A great value proposition, benefits for your personal career path and digital growth for your business. Find here some of the frequent questions and our answers about our professional diploma that will help you enroll.</p> -->
                <!-- <div class="accordion-option">
                    <a title="expand/collapse all" href="javascript:void(0)" class="toggle-accordion" accordion-id="#accordion">+</a>
                </div> -->
                <div class="clearfix"></div>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                	@if (!empty($qnas_categories))
                        @foreach ($qnas_categories as $key => $row)
                        	@if(in_array($row->id, $categoryQuestions))
	                        	<h3>{!! $row->name !!}</h3>
		                        @if (!empty($qnas))
			    	               @foreach ($qnas as $qkey => $qna)
			    	               @if($row->id == $categoryQuestions[$qna->id])
			                        <div class="panel panel-default">
			                            <div class="panel-heading" role="tab" id="qheading{{ $qkey }}">
			                                <h4 class="panel-title">
			                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#qcollapse{{ $qkey }}" aria-expanded="false" aria-controls="qcollapse{{ $qkey }}">
			                                        {!! $frontHelp->pField($qna, 'title') !!}
			                                    </a>
			                                </h4>
			                            </div>
			                            <div id="qcollapse{{ $qkey }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="qheading{{ $qkey }}">
			                                <div class="panel-body">
			                                    {!! $frontHelp->pField($qna, 'body') !!}
			                                </div>
			                            </div>
			                        </div>
			                        @endif
			    	               @endforeach
			    	           @endif
			    	        @endif
                        @endforeach
                    @endif
                </div>
    		</div>
    	</div>
    </div>
</div>

<!-- QnAs END -->
@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@include('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status])


<div class="page-content">
	<div class="post-content clearfix">
		<div class="container">

		    <h1>{{ $content->title }}</h1>
		    <h2 class="animatable fadeInUp">{{ $content->subtitle }}</h2>
		    <!-- <div class="page-quote"></div> -->
		    {!! $content->body !!}

		    <div class="row row-flex row-flex-wrap faqs-categories">
		    	<?php for ($i=1; $i < 13; $i++) { ?>
		    		
		    	
		    	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-10">
		    		<div class="faqs-cat-tile">
		    		<a href="#"><img src="{{cdn('theme/assets/faq-icons/faq<?php echo  $i; ?>.png')}}" alt="Sharing files and folders" title="Sharing files and folders">
		    			<div class="keyword-label">Sharing files and folders</div>
		    		</a>
		    		</div>
		    	</div>


		    <?php } ?>

		    </div>

		    <div class="row faqs-heading-bar">
		    	
		    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		    			<h2 class="faqs-heading">Frequently Asked Questions</h2>
		    		</div>
		    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		    			<div class="pull-right">
						{!! Form::open(['method'=>'GET','url'=>'blog','class'=>'navbar-form navbar-left','role'=>'search']) !!}
				        <div class="input-group custom-search-form">
				            <input type="text" name="faqsearch" class="form-control" placeholder="Search FAQs...">
				            <span class="input-group-btn">
				                <button type="submit" class="btn btn-newsletter andsearch">
				                    <i class="fa fa-search"></i>
				                </button>
				            </span>
				        </div>
				        {!! Form::close() !!}
						</div>
		    		</div>
		    		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

		    	
		    	
		    	
			    	<div class="faqs-list">
			    	<?php for ($i=1; $i < 13; $i++) { ?>	
		    			<span class="faq-label">
		    				<a href="#">
		    				Add licenses or storage space to a Dropbox Business account
		    				</a>
		    			</span><br />
			    	<?php } ?>
		    		</div>
		    	</div>
		    </div>

		</div>
	</div>
</div>

@endsection

@section('scripts')

@stop

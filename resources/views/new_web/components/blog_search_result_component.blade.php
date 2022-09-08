@php
   $results = 0;
   if(!empty($list)){
         $results += count($list);
   }

   $list = $dynamic_page_data["blog_search_data"]["list"] ?? null;
   $search_term = $dynamic_page_data["blog_search_data"]["search_term"] ?? null;
   $search_term_slug = $dynamic_page_data["blog_search_data"]["search_term_slug"] ?? null;

   $show_categories = false;

   foreach ($column->template->inputs as $input){
      $blog_display[$input->key] = $input->value ?? "";
   }
   $source = $blog_display["list_source"]->title;



@endphp

@if($source == "Blog" || $source == "Knowledge")

   <div class="search-results-head mb-0">
      <h1 class="search-results-title">Search results for <span>{{ $search_term }}</span></h1>
         <?php
            $results = 0;
            if(!empty($list)){
               $results += count($list);
            }
         ?>
      @if($results > 0 )
         <p class="search-results-text"><span>{{$results}} result(s) </span> found containing the term <span>{{ $search_term }}.</span></p>
      @else
         <p class="search-results-text"><strong>{{$results}} result(s) </strong> were found containing the term <strong>{{ $search_term }}</strong>. Try again.</p>
      @endif
   </div>

   @if($results > 0)
      @include("new_web.components.lists.blog_list_component")
   @else
      <div class="mb-5"></div>
   @endif

@elseif($source == "Event")
   @include("new_web.components.event_search_result_component")
@endif
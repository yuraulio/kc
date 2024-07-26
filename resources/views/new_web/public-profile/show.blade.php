@extends("new_web.layouts.master")

@section('metas')

  @if(isset($dynamic_page_data['event']) && !$dynamic_page_data['event']['index'] )
    <meta name="robots" content="noindex, nofollow" />
  @endif

@stop
@section('blog-custom-css')
  <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">

  <style>
    #chatbase-bubble-button{
      bottom: 0.5rem !important;
      right: 4rem !important;
    }

    .fb_dialog_content iframe {
      margin-right: 3rem !important;
      margin-bottom: -1rem !important;
    }
  </style>
@endsection

@section("content")
  @if(config("binshopsblog.reading_progress_bar"))
    <div id="scrollbar">
      <div id="scrollbar-bg"></div>
    </div>
  @endif

  <main id="main-area" role="main" class="bootstrap-classes">
    <div id="app">
      {{-- This here is to render the menus --}}
      @foreach ($content as $data)
        @include("new_web.layouts.rows")
      @endforeach

      <div class="container ps-4 pe-4">
      <div class="row">
        <div class="col-md-6">
          @include('new_web.public-profile.partials.avatar')
        </div>
        <div class="col-md-6">
          <div class="mt-5 mb-5 m-0 cms-rich-text-editor text-editor-blockquote">
            <h1 class="tab-title">{{ $user->name }}</h1>
            <div>
              {!! nl2br(e($user->biography)) !!}
            </div>

            <div class="bootstrap-classes pt-4 ">
              <h5>My Work Availability</h5>
              @if($user->is_employee)<span class="badge bg-gray cursor-auto">Employee</span>@endif
              @if($user->is_freelancer)<span class="badge bg-gray cursor-auto">Freelance</span>@endif
            </div>

            <div class="bootstrap-classes pt-4 ">
              <h5>My Working Location Preference</h5>
              @if($user->will_work_remote)<span class="badge bg-gray cursor-auto">Remote</span>@endif
              @if($user->will_work_in_person)
                @foreach($user->cities->load('country') as $city)
                  <span class="badge bg-gray cursor-auto">{{ $city->name }}, {{ $city->country->name }}</span>
                @endforeach
              @endif

            </div>

            <div class="bootstrap-classes pt-4 ">
              <h5>My Skills</h5>
              @foreach($user->skills as $skill)
                <span class="badge bg-gray cursor-auto">{{ $skill->name }}</span>
              @endforeach
            </div>

            <div class="bootstrap-classes pt-4 ">
              <h5>My Career Paths</h5>
              @foreach($user->careerPaths as $careerPath)
                <span class="badge bg-gray cursor-auto">{{ $careerPath->name }}</span>
              @endforeach
            </div>

            <div class="bootstrap-classes pt-4 ">
              <h5>My Experience</h5>
              <span class="badge bg-gray cursor-auto">{{ __('work_experience.' . $user->work_experience->value) }}</span>
            </div>

          </div>
        </div>
      </div>
      </div>
    </div>
  </main>
@endsection


@if(isset($renderFbChat) && $renderFbChat)
  @section('fbchat')
    <script>
      window.chatbaseConfig = {
        chatbotId: "XsnNyFmqIh9qjjBBG7JUp",
      }
    </script>
    <script
      src="https://www.chatbase.co/embed.min.js"
      id="XsnNyFmqIh9qjjBBG7JUp"
      defer>
    </script>
  @endsection
@endif


@push('components-scripts')



@endpush

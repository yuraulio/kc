
@if($event)

<div class="text-center schedule-course-wrapper">
    <a target="_blank" href="/print/syllabus/{{$event['slugable']['slug']}}"  class="submit-btn btn btn-outline-success mt-4">Download schedule</a>
    {{--<button id="submit-btn" type="button" class="submit-btn btn btn-success mt-4">{{ __('Save') }}</button>--}}
</div>

@endif

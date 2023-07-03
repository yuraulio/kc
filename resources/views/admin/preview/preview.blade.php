
@if($slug)

{{--<div class="pl-lg-4">--}}
<div>
    <div class="text-right">
        <a href="/{{$slug->slug}}?preview=true" target="_blank" class="btn btn-outline-success mt-4 custom-btn-breadcrum">{{ __('Preview') }}</a>
    </div>
</div>

@endif

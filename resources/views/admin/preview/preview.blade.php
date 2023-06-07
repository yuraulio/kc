
@if($slug)

{{--<div class="pl-lg-4">--}}
<div>
    <div class="text-right">
        <a href="/{{$slug->slug}}?preview=true" target="_blank" class="btn btn-success mt-4">{{ __('Preview') }}</a>
    </div>
</div>

@endif

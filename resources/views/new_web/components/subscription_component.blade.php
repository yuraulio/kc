@php
    $subscription = [];
    foreach ($column->template->inputs as $input){
        $subscription[$input->key] = $input->value ?? "";
    }

    $overlap = $subscription['top_overlap'] ?? true;
    $overlap_class = $overlap ? 'form-overlap' : 'form-default';
@endphp


<div class="form-section">
    <div class="form-area-wrapper m-0">
        <div class="form-wrapper blue-form w-m-bottom {{ $overlap_class }}">
            
            <form method="GET" action="/myaccount/subscription/{{ $subscription['subscribe_event']->title }}/{{ $subscription['subscribe_plan']->title }}" id="doall" novalidate>
                <h3 class="form-h3 subscription">{{ $subscription['subscribe_title'] ?? "" }}</h3>
                <ul class="subs-page-list">
                    <li>
                        <img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checkmark-sqaure.svg')}}" alt=""> 
                        <span class="subs-page-span">Access to presentations</span>
                    </li>
                    <li>
                        <img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checkmark-sqaure.svg')}}" alt=""> 
                        <span class="subs-page-span"> Access to bonus files</span>
                    </li>
                    <li>
                        <img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checkmark-sqaure.svg')}}" alt=""> 
                        <span class="subs-page-span"> Access to videos</span>
                    </li>
                    <li>
                        <img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checkmark-sqaure.svg')}}" alt=""> 
                        <span class="subs-page-span"> Personal notes</span>
                    </li>
                </ul>
                <div class="submit-area-custom">
                    <button onClick="subscribe()" type="button" class="btn btn--md btn--primary subscription-enroll">{{ $subscription['subscribe_button'] ?? "" }}</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

@push('components-scripts')
    <script type="text/javascript">
        function subscribe() {
            @if(!Auth::user())
                $('.login-popup-wrapper').addClass('active');
            @else
                $('#doall').submit();
            @endif
        }
    </script>
@endpush

@php
    $instructor = [];
    foreach ($column->template->inputs as $input){
        $instructor[$input->key] = $input->value ?? "";
    }

    $data = $dynamic_page_data;
    $content = $data["content"];
    $instructorTeaches = $data["instructorTeaches"];
@endphp

<div class="pb-4 pt-4">
    @if(count($instructorTeaches) >0)
        <div class="instructor-area instructor-studies">
            <h2>{{ $content->title }} {{ $content->subtitle }} teaches:</h2>
            <ul>
                @foreach($instructorTeaches as $teach)
                    <li><img loading="lazy" class="resp-img" width="25" height="25" src="{{cdn('/theme/assets/images/icons/graduate-hat.svg')}}" title="graduate-hat.svg" alt="graduate-hat-icon">{!! $teach !!}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>


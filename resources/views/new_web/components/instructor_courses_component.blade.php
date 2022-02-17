@php
    $instructor = [];
    foreach ($column->template->inputs as $input){
        $instructor[$input->key] = $input->value ?? "";
    }

    $data = $page->getInstructorCourses();
    $content = $data["content"];
    $instructorTeaches = $data["instructorTeaches"];
@endphp

<div class="pb-4 pt-4">
    @if(count($instructorTeaches) >0)
        <div class="instructor-area instructor-studies">
            <h2>{{ $content->title }} {{ $content->subtitle }} teaches:</h2>
            <ul>
                @foreach($instructorTeaches as $teach)
                    <li><img width="25" src="{{cdn('/theme/assets/images/icons/graduate-hat.svg')}}" alt="">{{$teach}}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>


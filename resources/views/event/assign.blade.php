@extends('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Event Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Assign Event') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ $event->title }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="assign_form" method="post" action="{{ route('events.assign_store', $event) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('post')

                            <h3 class="error" style="color:red; display:none;">Plese select instructor for all lessons</h3>

                            <h6 class="heading-small text-muted mb-4">{{ __('Event assign') }}</h6>
                            <div class="pl-lg-4">

                            <?php //dd($topics); ?>

                            <div class="form-group{{ $errors->has('topic_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-topic_id">{{ __('Topic') }}</label>
                                    <select multiple name="topic_id[]" id="input-topic_id" class="form-control topics" placeholder="{{ __('Topic') }}" required>
                                        <option value="">-</option>
                                        @foreach ($topics as $topic)
                                            <option value="{{ $topic->id }}:{{$topic->title}}" > {{ $topic->title }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'topic_id'])
                                </div>


                                <div id="container">

                                </div>


                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')

<script>

let isValid = [];

    $( "#input-topic_id" ).change(function() {
        const instructors = @json($instructors);
        $("#container").children().remove();
        let topic_ids = $('#input-topic_id').val();

        a = [];

        topic_ids.forEach(function callbackFn(element) {
            split_phrase = element.split(":");
            a.push({'name' : split_phrase[1], 'value' : split_phrase[0]})
        })

        for(var i=0; i<topic_ids.length; i++){
            let row = `
            <label class="form-control-label" for="input-topic_id">{{ __('Lesson for topic : ') }}${a[i].name}</label>
            <div id="${a[i].value}"></div>


            `;

            $('#container').append(row);
        }

        $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                  method: 'POST',
                  url: '/events/fetchTopics',
                  data:{'topics_ids' : a},
                  success: function(data) {
                      let i = 0;

                      data = JSON.parse(data)
                      //console.log(data)
                      data.forEach(function callbackFn(element) {
                        topicId = element['id']
                        lessons = element.lessons
                        $.each(lessons,function (index, element1) {
                            if(element.event_topic.length != 0){
                                instructor_id = element.event_topic[i]['pivot']['instructor_id']
                                i = i + 1;
                            }else{
                                instructor_id = 0
                            }

                            let row = `
                            <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">${element1['title']}</label>
                            </div>
                            <select class="custom-select" data-topic="${topicId}" id="lesson-${element1['id']}" >
                                <option>Instructors...</option>
                            </select>
                            </div>
                            `

                            $("#"+element['id']).append(row);

                            //console.log(instructors);
                            instructors.forEach(function callbackFn(elem) {
                                let row1 = (elem['id'] == instructor_id) ? '<option selected ' : '<option '
                                let row2 = `value="${elem['id']}">`+elem['title']+`</option>`;
                                $('#lesson-'+element1['id']).append(row1+row2);
                            });
                        })


                      });


                    }
        })
    });

    $('#assign_form').submit(function() {
    // DO STUFF...
    alert('before submit')
    let new_data = []
    $('.custom-select option:selected').each(function(e) {
        let lesson_name = $(this).closest('.custom-select').attr("id")
        let topic_id = $(this).closest('.custom-select').data('topic')
        let lesson_id = $(this).val()

        new_data.push({'name' : lesson_name, 'topic_id' : topic_id ,'lesson_id' : lesson_id})



        if($(this).val() == 'Instructors...'){
            isValid.push("false")
        }else{
            isValid.push("true")
        }


    });

    $('<input />').attr('type', 'hidden')
            .attr('name', 'all_lessons')
            .attr('value', JSON.stringify(new_data))
            .appendTo('#assign_form');


    if(isValid.includes("false")){
        $('.error').css("display", "block")
        isValid = []
        return false;
    }


     // return false to cancel form action
});








</script>

@endpush

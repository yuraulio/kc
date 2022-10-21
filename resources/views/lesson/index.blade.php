@extends('layouts.app', [
    'title' => __('Lesson Management'),
    'parentSection' => 'laravel',
    'elementName' => 'lessons-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot
            @slot('filter')
                <!-- <a href="#" class="btn btn-sm btn-neutral">{{ __('Filters') }}</a> -->
                <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">{{ __('Filters') }}</a>

            @endslot

            <li class="breadcrumb-item"><a href="{{ route('lessons.index') }}">{{ __('Lessons Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Lessons') }}</h3>
                            </div>
                                <div class="col-4 text-right">
                                    <a href="{{ route('lessons.create') }}" class="btn btn-sm btn-primary">{{ __('Add Lesson') }}</a>
                                </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="collapse" id="collapseExample">
                        <div class="container">
                            <div class="row">

                                <div class="col-sm-4 filter_col" id="filter_col2" data-column="1">
                                    <label>Categories</label>
                                    <select data-toggle="select" data-categoryy="" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col2_filter">
                                    <option id="allCat" data-categoryy="" selected value="-- All --"> -- All -- </option>
                                    </select>
                                </div>

                                <div class="col-sm-4 filter_col" id="filter_col1" data-column="2">
                                    <label>Topics</label>
                                    <select data-topic="" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                    <option data-topic="" selected id="allTop" value="-- All --"> -- All -- </option>
                                    </select>
                                </div>

                                <div class="col-sm-4 filter_col" id="filter_col3" data-column="3">
                                    <label>Status</label>
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col3_filter">
                                        <option></option>
                                        <option value="Published">Published</option>
                                        <option value="Unpublished">Unpublished</option>
                                    </select>
                                </div>
                                
                                    <div class="col-sm-4 filter_col hidden" id="move_col1">
                                        <label>Move To Topic</label>
                                        <div class="is-flex">
                                            <select data-topic="" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="form-control" id="col1_move">
                                                <option selected id="allTop" value="-- All --"> -- All -- </option>
                                            </select>
                                            <button id="move-lesson" class="btn btn-primary" type="button"> Move </button> 
                                        </div>
                                    </div>
                                    
                                

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic31">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Select') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Assigned Topic') }}</th>
                                    <th class="" scope="col">{{ __('Assigned Categories') }}</th>
                                    <th class="hidden" scope="col">{{ __('Order') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="lessons-order">
                                @foreach ($lessons as $lesson)
                             

                               

                                @foreach($lesson->topic as $key => $topic)
                                
                                
                                
                                    <tr class="lesson-list">
                                        <td> 
                                            <div class="input-group-prepend lesson-select">
                                                <div class="input-group-text">
                                                    <input data-category-id="{{$lesson->category[$key]['id']}}" data-lesson-id="{{$lesson->id}}" class="check-lesson" type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                            </div> 
                                        </td>
                                        <td><?= ($lesson->status == 1) ? 'Published' : 'Unpublished'; ?></td>
                                        <td class="lesson-title-{{$lesson->id}}"><a href="{{ route('lessons.edit', [$lesson,'selectedCategory' => $lesson->category[$key]['id']]) }}">{{ $lesson->title }}</a></td>
                                        <td id="{{$lesson->category[$key]['id']}}-{{$topic->id}}-{{$lesson->id}}">
                                       
                                            {{ $topic->title }},
                                       
                                        </td>
                                        <td class="">
                                       
                                                {{ $lesson->category[$key]['name'] }},
                                         
                                        </td>
                                       
                                        <td id="order-{{$lesson->category[$key]['id']}}-{{$topic->id}}-{{$lesson->id}}" data-priority="{{$lesson->category[$key]['id']}}-{{$topic->id}}-{{$lesson->id}}" data-priority-value="{{ $topic->pivot->priority }}" class="hidden order-priority">
                                       
                                                {{ $topic->pivot->priority }}
                                         
                                        </td>

                                        <td class="text-right">

                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('lessons.edit', $lesson) }}">{{ __('Edit') }}</a>
                                                    <form id="deleteLesson" action="{{ route('lessons.destroy', $lesson) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this lesson?") }}') ? deleteLesson() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>


                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script>

        var categories = @json($categories);
        var selectedTopic = null
        var selectedCategory = null
        var count = 0
        var selectedStatus = null
        var table = $('#datatable-basic31').DataTable({
            destroy: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[ 5, "asc" ]],
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        function removeSpecial(s){
            s = s.replace(/ /g,'');
            s = s.replace(/&/g,'');
            s = s.replace(/amp;/g,'');
            s = s.replace(/,/g, "");
            return s
        }


   
            $(function() {

    $("#col2_filter").select2({
    templateResult: function(option, container) {


        if(selectedTopic != null){
            if (selectedCategory != '' && $(option.element).attr("data-category") !== undefined && removeSpecial($(option.element).attr("data-category")) != selectedCategory){
                $(container).css("display","none");
            }
            return option.text;
        }else{
            return option.text;
        }

    }
    });
    
    
    $("#col1_filter").select2({
    templateResult: function(option, container) {
        
        if(selectedCategory != null){
            if (selectedCategory != '' && $(option.element).attr("data-category") !== undefined && removeSpecial($(option.element).attr("data-category")) != selectedCategory){
                $(container).css("display","none");
            }
            return option.text;
        }else{
            return option.text;
        }

    }
    });
    
    $("#col1_move").select2({
    templateResult: function(option, container) {

        if(selectedCategory != null){
            if (selectedCategory != '' && $(option.element).attr("data-category") !== undefined && removeSpecial($(option.element).attr("data-category")) != selectedCategory){
                $(container).css("display","none");
            }
            return option.text;
        }else{
            return option.text;
        }

    }
    });
    
    $.each(categories, function(key, value) {
    
    let topics = value[0].topics
    let row = `<option data-categoryy="${value[0].id}" value="${key}">${key}</option>`
    $('#col2_filter').append(row)

    $.each(topics, function(key1, value1) {
        let row = `<option data-topic="${value1.id}" data-category="${key}" value="${removeSpecial(value1.title)}">${value1.title}</option>`
        $('#col1_filter').append(row)
        $('#col1_move').append(row)
    })
    })
    
    
    /////
    function searchByFilters(){
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {

            let found_from_topi = false
            let found_from_cat = false
            let found = false
            let status = data[1]
        
            //let global_search = $('.dataTables_filter input').val()
        
            // if(global_search != '' && global_search.length > 3 && selectedTopic == null && selectedCategory == null){
            //     if(data[1].includes(global_search) ){
            //         found = true
            //     }
            // }else{
            //     found = true
            // }
            
            
            
            if(selectedCategory != null){
                
        
                if(selectedCategory != '' && selectedTopic == null){
            

                    let word = removeSpecial(data[4]);
                    word = word.split(',');
            
            
                    if(selectedCategory != "--All--"){
                    if(selectedCategory != ''){
                        if(word.length != 0){
                            $.each(word, function(key, value) {
                                if(value != ''){
                                    if(removeSpecial(value) == selectedCategory){
                                        found = true;
                                        if(selectedStatus != null){
                                            if(selectedStatus == status){
                                            
                                            }else{
                                                found = false
                                            }
                                        }
                                    
                                    }
                                }
                            })
                        
                        }
                    }else{
                        found = true
                    }
                    }else{

                    found = true
                    if(selectedStatus != null){
                        if(selectedStatus == status){
                        
                        }else{
                            found = false
                        }
                    }
                    }
            
            
                }else if(selectedCategory != null && selectedTopic != ''){
            
                    let cat = removeSpecial(data[4]);
                    cat = cat.split(',');

                    let topi = removeSpecial(data[3]);
                    topi = topi.split(',');


                    if(selectedTopic != '--All--'){
                        $.each(cat, function(key, value) {
                            if(value != ''){
                                if(removeSpecial(value) == selectedCategory){
                                    //alert('found')
                                    found_from_cat = true
                                }
                            }
                        })
                        $.each(topi, function(key, value) {
                            if(value != ''){
                                if(removeSpecial(value) == selectedTopic){
                                    //alert('found')
                                    found_from_topi = true
                                }
                            }
                        })
                
                        if(found_from_topi && found_from_cat){
                            found = true
                            if(selectedStatus != null){
                                if(selectedStatus == status){
                                    found = true
                                }else{
                                    found = false
                                }
                            }
                        
                        }
                    }else{
                        $.each(cat, function(key, value) {
                            if(value != ''){
                                if(removeSpecial(value) == selectedCategory){
                                    //alert('found')
                                    found_from_cat = true
                                }
                            }
                        })
                        if(found_from_cat){
                            found = true
                            selectedTopic = null
                        }
                
                    }
            
                }else if(selectedCategory == null && selectedTopic != null){
                let word = data[3].split(',');
            
                if(selectedTopic != '--All--'){
                    if(selectedCategory != ''){
                    
                    if(word.length != 0){
                        $.each(word, function(key, value) {
                            if(value != ''){
                                if(removeSpecial(value) == selectedTopic){
                                    //alert('found')
                                    found = true
                                    if(selectedStatus != null){
                                        if(selectedStatus == status){
                                        
                                        }else{
                                            found = false
                                        }
                                    }
                                }
                            }
                        })
                    
                    }
                    }else{
                    found = true
                    }
                }else{
                    found = true
                    if(selectedStatus != null){
                        if(selectedStatus == status){
                        
                        }else{
                            found = false
                        }
                    }
                }
            
            
            
                }
                if(found){
                    return true;
                }
                return false;
            }else{
                return true
            }
            return true
    
    
    
        }
    );
    }
    /////
    $('#col1_filter').change(function() {
        
        initCheckBox();
        $("#col1_filter").data('topic',$("option:selected", this).data('topic'))
        selectedTopic = removeSpecial($(this).val())
        
        searchByFilters()
        table.draw();

    })
    
    $('#col1_move').change(function() {
        $("#col1_move").data('topic',$("option:selected", this).data('topic'))
    })
    
    $('#col2_filter').change(function() {
        initCheckBox();
        $("#col2_filter").data('categoryy',$("option:selected", this).data('categoryy'))

        if(count != 0){
            if($(this).val() == '-- All --'){
                $("#col1_filter").val("-- All --").change();
                $("#col1_move").val("-- All --").change();

            }
        }
        selectedCategory = removeSpecial($(this).val())
        searchByFilters()
        table.draw();
        count = count + 1
    })
    
    $('#col3_filter').change(function() {
        selectedStatus = $(this).val()
        searchByFilters()
        table.draw();
    })

});

        



        $(document).ready(function(){
            
            $('#col2_filter').change();
        })

        $('#datatable-basic31_filter').on( 'keyup', function () {
            var table = $('#datatable-basic31').DataTable();
            
            table
                .columns( 2 )
                .search( $(this).find('input').val() )
                .draw();
        } );

        $("#move-lesson").click(function(){


            $( document ).ajaxStart(function() {
                window.swal({
                    title: "Move lessons...",
                    text: "Please wait",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            });

            let category = $("#col2_filter").data('categoryy');
            let fromTopic = $("#col1_filter").data('topic');
            let toTopic = $("#col1_move").data('topic');
            let toTopicName = $("#col1_move").val();

            
            let lessons = [];
            $('.check-lesson').each(function(index, value) {
                if ($(this).is(':checked')) {
                    lessons.push($(this).data('lesson-id'))
                }
            });

            let data = {'lessons':lessons, "category":category,'fromTopic':fromTopic,'toTopic':toTopic}
            let message = '';

            $.ajax({
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
   	            type: 'post',
   	            url: '{{route("move-multiple-lessons")}}',
                data: data,
   	            
                success: function (data) {
                   
                    if(data['success']){


                        $.each(lessons,function(index, value){   

                            if(index > 0){
                                message += ', ' + $(`.lesson-title-${value}`).text();
                            }else{
                                message +=  $(`.lesson-title-${value}`).text();
                            }

                            $(`#${category}-${fromTopic}-${value}`).html( toTopicName + ',')
                            $(`#${category}-${fromTopic}-${value}`).attr("id",`${category}-${toTopic}-${value}`);

                            $(`#order-${category}-${fromTopic}-${value}`).attr("data-priority",`${category}-${toTopic}-${value}`);
                            $(`#order-${category}-${fromTopic}-${value}`).attr("data-priority-value",data.newOrder[`${category}-${toTopic}-${value}`]);
                            $(`#order-${category}-${fromTopic}-${value}`).text(data.newOrder[`${category}-${toTopic}-${value}`]);

                            $(`#order-${category}-${fromTopic}-${value}`).attr("id",`#order-${category}-${toTopic}-${value}`);

                            

                        });


                        if(lessons.length > 1 ){
                            message = 'The lessons ' + message + ' are moved to ' + toTopicName + '.'
                        }else{
                            message = 'The lesson ' + message + ' is moved to ' + toTopicName + '.'
                        }

                        initCheckBox()

                        $('#datatable-basic31').dataTable().fnDestroy();
                        table = $('#datatable-basic31').DataTable({
                            destroy: true,
                            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                            "order": [[ 5, "asc" ]],
                            language: {
                                paginate: {
                                next: '&#187;', // or '→'
                                previous: '&#171;' // or '←'
                                }
                            }
                        });

                       


                        $('#col2_filter').change();
                        $('#col1_filter').change();

                        $(".success-message p").html(message);
                        $(".success-message").show();

                        window.swal({
                            title: message,
                            showConfirmButton: false,
                            timer: 2000
                        });


                    }else{
                        let errorMessage = '';
                        $.each(data.errors,function(index, value){
                            $.each(value,function(index1, value1){
                                errorMessage += value1 + ' ';
                            });
                           
                        });
                        $(".error-message p").html(errorMessage);
                        $(".error-message").show();
                        window.swal({
                        title: errorMessage,
                        showConfirmButton: false,
                        timer: 2000
                    });

                        
                    }
                    $("#col1_move").val("-- All --").change();
                }
            });

        })

    </script>

    <script>

        function initCheckBox(){

            $('.check-lesson').each(function(){
                $(this).prop('checked', false);
            });
            $("#move_col1").hide()
        }

        //$(".check-lesson").click(function(){
        $(document).on("click",".check-lesson",function(){
            let clicked = false;
            $('.check-lesson').each(function(index, value) {
                if ($(this).is(':checked')) {
                    clicked = true;
                }
            });
            
            if(clicked){
                $("#move_col1").show()
            }else{
                $("#move_col1").hide()
            }
        });
    </script>


<script src="{{ asset('js/sortable/Sortable.js') }}"></script>
<script>
    let category ;
    let topic;
    let lessons;

    (function( $ ){
        
        
        lessons = {};
        var el

        $( ".lessons-order" ).each(function( index ) {

            el = document.getElementsByClassName('lessons-order')[index];
            new Sortable(el, {
               
                multiDrag: true,
                selectedClass: 'selected',
                
                onSelect: function ( /**Event*/ evt) {
                    initOrder()
                },

                /*onStart: function (evt) {
                    initOrder()
                },*/

                // Element dragging ended
                onEnd: function ( /**Event*/ evt) {           
                   
                    orderLessons()
                },

            });
           

        });

       
    })( jQuery );

    function initOrder(){
        
        lessons = {};
        let order = 0;

       $( ".lesson-list .order-priority" ).each(function( index ) {
            
            if(index == 0){
                //order = Number($(this).html());
                order = Number($(this).data('priority-value'));
                lessons[$(this).data('priority')] = order;
                lessons[index] = order;
            }else{
                order += 1;
                lessons[$(this).data('priority')] = order;
                lessons[index] = order;
            }
            //console.log('index = ' + index + ' order = ' + order)


       });

    
   }

    function orderLessons(){
        let newOrder = {};
        category = $("#col2_filter").data('categoryy');
        topic = $("#col1_filter").data('topic');
        $( ".lesson-list .order-priority" ).each(function( index ) {

            let lessonPrioId = $(this).data('priority').split('-')[2]
            newOrder[`${category}-${topic}-${lessonPrioId}`] = lessons[index]
        });

       
        data = {'category':category,'topic':topic,'order':newOrder}
        //console.log(data);
        $( document ).ajaxStart(function() {
            window.swal({
                title: "Change Order...",
                text: "Please wait",
                showConfirmButton: false,
                allowOutsideClick: false
            });
        });

        $.ajax({
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            Accept: 'application/json',
            url: "{{ route ('sort-lessons') }}",
            data:data,
            success: function(data) {
                if(data['success']){

                    /*$( ".lesson-list .order-priority" ).each(function( index ) {
                        $(this).html(lessons[index])
                    });*/

                    $.each(data.newOrder,function(index, value){
                            $(`#order-`+index).html( value );
                            $(`#order-`+index).attr("data-priority-value",value);
                    })

                    initCheckBox()
                    $('#datatable-basic31').dataTable().fnDestroy();
                    table = $('#datatable-basic31').DataTable({
                        destroy: true,
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        "order": [[ 5, "asc" ]],
                        language: {
                            paginate: {
                            next: '&#187;', // or '→'
                            previous: '&#171;' // or '←'
                            }
                        }
                    });
                    $('#col2_filter').change();
                    $('#col1_filter').change();

                    window.swal({
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2000
                    });

                   
                }else{
                    let errorMessage = '';
                    $.each(data.errors,function(index, value){
                        $.each(value,function(index1, value1){
                            errorMessage += value1 + ' ';
                        });
                       
                    });

                    window.swal({
                        title: errorMessage,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    {{--$(".error-message p").html(errorMessage);
                    $(".error-message").show();--}}
                }

            },
            error: function(data) {

                $.each(data.errors,function(index, value){
                        $.each(value,function(index1, value1){
                            errorMessage += value1 + ' ';
                        });
                       
                    });


                window.swal({
                        title: errorMessage,
                        showConfirmButton: false,
                        timer: 2000
                    });
            }
        });

       
    }

</script>

<script>

    function deleteLesson(){
        let lessons = [];
        let categories = [];
        $('.check-lesson').each(function(index, value) {
            if ($(this).is(':checked')) {
                lessons.push($(this).data('lesson-id'))
                categories.push($(this).data('category-id'))
            }
        });

    
        let lessonsInput = '';
        let categoryInput = '';
        $.each( lessons, function( key, value ) {
            lessonsInput += `<input class="hidden" name="lessons[]" value="${value}">`
            categoryInput += `<input class="hidden" name="categories[]" value="${categories[key]}">`
        });

    
        $("#deleteLesson").append(lessonsInput);
        $("#deleteLesson").append(categoryInput);
        $("#deleteLesson").submit();

    }

</script>

@endpush

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
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col2_filter">
                                    <option selected value="-- All --"> -- All -- </option>
                                    </select>
                                </div>

                                <div class="col-sm-4 filter_col" id="filter_col1" data-column="2">
                                    <label>Topics</label>
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                    <option selected value="-- All --"> -- All -- </option>
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

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic31">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Assigned Topic') }}</th>
                                    <th class="" scope="col">{{ __('Assigned Categories') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lessons as $lesson)
                                    <tr>
                                        <td><?= ($lesson->status == 1) ? 'Published' : 'Unpublished'; ?></td>
                                        <td><a href="{{ route('lessons.edit', $lesson) }}">{{ $lesson->title }}</a></td>
                                        <td>
                                        @foreach($lesson->topic as $topic)
                                            {{ $topic->title }},
                                        @endforeach
                                        </td>
                                        <td class="">
                                        @foreach($lesson->topic as $topic)
                                        <?php //dd($topic['category'][0]); ?>
                                            {{ $topic['category'][0]['name'] }},
                                        @endforeach
                                        </td>

                                        <td class="text-right">

                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('lessons.edit', $lesson) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('lessons.destroy', $lesson) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>


                                                </div>
                                            </div>
                                        </td>

                                    </tr>
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
        var selectedStatus = null
        var table = $('#datatable-basic31').DataTable({
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
            return s
        }



        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                let found_from_topi = false
                let found_from_cat = false
                let found = false
                let status = data[0]

                //let global_search = $('.dataTables_filter input').val()

                // if(global_search != '' && global_search.length > 3 && selectedTopic == null && selectedCategory == null){
                //     if(data[1].includes(global_search) ){
                //         found = true
                //     }
                // }else{
                //     found = true
                // }



                if(selectedCategory != '' && selectedTopic == null){
                    //console.log('first')

                    let word = data[3].split(',');


                    if(selectedCategory != "--All--"){
                        if(selectedCategory != ''){
                            if(word.length != 0){
                                //console.log(word)
                                $.each(word, function(key, value) {
                                    if(value != ''){
                                        if(removeSpecial(value) == selectedCategory){
                                            found = true;
                                            if(selectedStatus != null){
                                                //console.log('has status')
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
                        //console.log('from all')
                        found = true
                        if(selectedStatus != null){
                            //console.log('has status')
                            if(selectedStatus == status){

                            }else{
                                found = false
                            }
                        }
                    }


                }else if(selectedCategory != null && selectedTopic != ''){
                    //console.log('second')

                    let cat = data[3].split(',');
                    let topi = data[2].split(',');

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
                                //console.log('has status')
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
                    //console.log('third')
                    let word = data[2].split(',');

                    if(selectedTopic != '--All--'){
                        if(selectedCategory != ''){

                        if(word.length != 0){
                            //console.log(word)
                            $.each(word, function(key, value) {
                                if(value != ''){
                                    if(removeSpecial(value) == selectedTopic){
                                        //alert('found')
                                        found = true
                                        if(selectedStatus != null){
                                            //console.log('has status')
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
                            //console.log('has status')
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

            }
        );


        $(function() {

            function filterGlobal () {
                table.draw();

            }

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

            $.each(categories, function(key, value) {
                let topics = value[0].topics
                //console.log(topics)
                let row = `<option value="${key}">${key}</option>`
                $('#col2_filter').append(row)

                $.each(topics, function(key1, value1) {
                    let row = `<option data-category="${key}" value="${value1.title}">${value1.title}</option>`
                    $('#col1_filter').append(row)
                })
            })


            $('#col1_filter').change(function() {
                selectedTopic = removeSpecial($(this).val())
                table.draw();
            })

            $('#col2_filter').change(function() {
                selectedCategory = removeSpecial($(this).val())
                table.draw();
            })

            $('#col3_filter').change(function() {
                selectedStatus = $(this).val()
                table.draw();
            })


        });





    </script>
@endpush

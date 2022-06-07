@extends('layouts.app', [
    'title' => __('Instructor Management'),
    'parentSection' => 'laravel',
    'elementName' => 'instructors-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot
            <li class="breadcrumb-item"><a href="{{ route('instructors.index') }}">{{ __('Ads feed') }}</a></li>
        @endcomponent
    @endcomponent

    <div class="card shadow">
        <div class="card-body">

            <div class="form-group">
                <label class="form-control-label" for="input-method">{{ __('Update feeds') }}</label>
                <div class="form-group">

                    <a href="javascript:void(0)" id="update-feed-btn" class="btn btn-primary">Update feeds</a>

                </div>
            </div>

            <div class="form-group{{ $errors->has('') ? ' has-danger' : '' }}">
                <label class="form-control-label" for="input-category_id">{{ __('Feeds update') }}</label>
                {{--<select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Feeds update') }}" required>
                    <option></option>
                    @foreach ($categories as $category)
                        <option <?php if(count($event->category) != 0){
                            if($event->category[0]->id == $category->id){
                                echo 'selected';
                            }else{
                                echo '';
                            }
                        }
                        ?> value="{{ $category->id }}" >{{ $category->name }}</option>
                    @endforeach
                </select>

                @include('alerts.feedback', ['field' => ''])--}}

                Coming Soon

            </div>

            <div class="form-group">
                <label class="form-control-label" for="input-method">{{ __('Download feeds') }}</label>
                <div class="form-group">

                    <a href="https://knowcrunch.com/csv/google/google.csv" class="btn btn-primary">Feed for Google advertising</a>

                </div>

                <div class="form-group">

                    <a href="https://knowcrunch.com/csv/fb/fb.csv" class="btn btn-primary">Feed for Facebook advertising</a>

                </div>
            </div>

     

        </div>
    </div>



@endsection
@push('js')

<script>

    $(document).on("click", "#update-feed-btn", function(){

        window.swal({
            title: "Feed Updating...",
            text: "Please wait",
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({

            type: 'get',
            url: '/fb-google-csv',

            success: function (data) {

                window.swal({
                    title: "Finished!",
                    showConfirmButton: false,
                    timer: 2000
                });

            },
            error: function() {
                //console.log(data);
            }


        })
    })

</script>
@endpush

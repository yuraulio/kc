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

                <div class="form-group">

                    <a href="javascript:void(0)" data-filename="tiktok_feed.xml" data-path="{{url('/')}}{{'/xml/tiktok/tiktok_feed.xml'}}" class="btn btn-primary xml">Feed for TikTok advertising</a>

                </div>
                <div class="form-group">

                    <a href="javascript:void(0)" data-filename="pinterest_feed.xml" data-path="{{url('/')}}{{'/xml/pinterest/pinterest_feed.xml'}}" class="btn btn-primary xml">Feed for Pinterest advertising</a>

                </div>
            </div>

            <div class="form-group" style="width:415px;">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Google</span>
                  </div>
                  <input value="https://knowcrunch.com/csv/google/google.csv" type="text" aria-label="google-csv" class="form-control" readonly>
                </div>
            </div>
            
            <div class="form-group" style="width:415px;">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Facebook</span>
                  </div>
                  <input value="https://knowcrunch.com/csv/fb/fb.csv" type="text" aria-label="facebook-csv" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group" style="width:415px;">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">TikTok</span>
                  </div>
                  <input value="https://knowcrunch.com/xml/tiktok/tiktok_feed.xml" type="text" aria-label="tiktok-xml" class="form-control" readonly>
                </div>
            </div>
            
            <div class="form-group" style="width:415px;">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Pinterest</span>
                  </div>
                  <input value="https://knowcrunch.com/xml/pinterest/pinterest_feed.xml" type="text" aria-label="pinterest-xml" class="form-control" readonly>
                </div>
            </div>

     

        </div>
    </div>



@endsection
@push('js')

<script>

    function downloadFile(url, fileName) {
    const downloadLink = document.createElement('a');
    downloadLink.href = url;
    downloadLink.download = fileName;
    downloadLink.target = "_blank";

    // Append the link to the DOM (this is required for the download to work in some browsers)
    document.body.appendChild(downloadLink);

    // Click the link to start the download
    downloadLink.click();

    // Remove the link (it's not needed anymore)
    document.body.removeChild(downloadLink);
    }



    $(document).on("click", ".xml", function(){
  
        let elem = $(this)[0];
        let fileURL = $(elem).data('path');
        let fileName = $(elem).data('filename');

        downloadFile(fileURL, fileName);


    })

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

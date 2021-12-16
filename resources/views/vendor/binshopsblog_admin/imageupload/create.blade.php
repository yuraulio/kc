@extends("binshopsblog_admin::layouts.admin_layout")
@section("content")
<div class="card mb-0">
    <div class="card-header" style="border-bottom: 0px">
        <div class="row align-items-center">
            <div class="col-8">
                <a href="{{ route('binshopsblog.admin.images.all') }}" class="btn btn-sm btn-info"> <i class="fas fa-arrow-left"></i> {{ __('Back to Images') }}</a>
            </div>
            <div class="col-4 text-right">
        </div>
    </div>
    <br>
    <div>
        <form method='post' action='{{route("binshopsblog.admin.images.store")}}' enctype="multipart/form-data">

            @csrf


            <div class="form-group mb-4 p-2">

                <small id="image_title_help" class="form-text text-muted">Image Title</small>
                <input required class="form-control" type="text" name="image_title" id="image_title"
                    aria-describedby="image_title_help">

            </div>


            <div class="form-group mb-4 p-2">

                <small id="blog_upload_help" class="form-text text-muted">Upload image</small>
                <input required class="form-control" type="file" name="upload" id="upload"
                    aria-describedby="upload_help">

            </div>


            <div class="form-group mb-4 p-2">

                <h4 >Sizes to convert to:</h4>

                <div>
                    <input type='checkbox' name='sizes_to_upload[binshopsblog_full_size]' value='true' checked id='size_binshopsblog_full_size'>
                <label for='size_binshopsblog_full_size'>Full size (no resizing)</label>
                    </div>


                @foreach((array)config('binshopsblog.image_sizes') as $size => $image_size_details)
                <div>
                    <input type='checkbox' name='sizes_to_upload[{{$size}}]' value='true' checked id='size_{{$size}}'>
                    <label for='size_{{$size}}'>{{$image_size_details['name']}} - {{$image_size_details['w']}} x {{$image_size_details['h']}}px</label>
                </div>
                    @endforeach

            </div>
            <div class="form-group mb-4 p-2">
                <input type='submit' class='btn btn-success' value='Upload'>
            </div>
        </form>
    </div>
</div>
@endsection

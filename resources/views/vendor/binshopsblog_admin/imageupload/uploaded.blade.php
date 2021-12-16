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
        @forelse($images as $image)

        <div class="row">
            <div class="col-12">
                <h4>{{$image['filename']}}</h4>
                <h6>
                    <small>{{$image['w'] . "x" . $image['h']}}</small>
                </h6>

                <a href='{{asset(     config("binshopsblog.blog_upload_dir") . "/". $image['filename'])}}' target='_blank'>
                    <img src='{{asset(     config("binshopsblog.blog_upload_dir") . "/". $image['filename'])}}'
                         style='max-width:400px; height: auto;'>
                </a>
                <br>
                <input type='text' readonly='readonly' class='form-control' value='{{asset(     config("binshopsblog.blog_upload_dir") . "/". $image['filename'])}}'>
                <input type='text' readonly='readonly' class='form-control' value='{{"<img src='".asset(     config("binshopsblog.blog_upload_dir") . "/". $image['filename'])."' alt='' >"}}'>

            </div>

        </div>
        <br>

        @empty
            <div class='alert alert-danger'>No image was processed</div>
        @endforelse
    </div>
</div>
@endsection

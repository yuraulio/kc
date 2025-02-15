<link href="{{ cdn(mix('theme/assets/css/bootstrap.css')) }}" rel="stylesheet" media="all" />
<link href="{{asset('admin_assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('admin_assets/css/saas/app-limited.css')}} " rel="stylesheet" type="text/css"/>

<style>
    .ubold .form-control {
        height: initial!important;
    }
    .ubold .search-bar .form-control-light {
        padding-left: 40px;
        padding-right: 20px;
        border-radius: 30px;
        background-color: #f3f7f9 !important;
        border-color: #f3f7f9 !important;
    }
    .ubold .search-bar span {
        position: absolute;
        z-index: 10;
        font-size: 16px;
        line-height: calc(1.5em + 0.9rem + 2px);
        left: 13px;
        top: -2px;
        color: #98a6ad;
    }
    .ubold .table-action-btn.dropdown-toggle.arrow-none.btn.btn-light.btn-xs::before {
        display: none;
    }
    .ubold .image-input-button {
        color: #6658dd;
        background-color: rgba(102, 88, 221, 0.18);
        border-color: rgba(102, 88, 221, 0.12);
        border-radius: 0.15rem;
    }
    .ubold .image-input-button:hover {
        color: #fff;
        background-color: #6658dd;
    }
    #version-btn {
        display: none;
    }
</style>

<?php
    if(isset($versions)){
        $versions = json_encode($versions);
    }
    if(isset($image_version)){
        $image_version = $image_version;
    }else{
        $image_version = '';
    }

    $imageedit = "false";
    use App\Model\Admin\MediaFile;
    $image = null;
    if($event && $event['path'] != null) {
        $imageedit = "true";
        $name = $event['original_name'];
        $path = str_replace("//", "/", $event['path']);

        $prefix = '/uploads';
        $str = $path;
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }

        $version_image = explode('.',$name);
        $extension = $version_image[1];
        $version_image = $version_image[0].'-'.$image_version.$extension;

        if(file_exists( public_path($prefix.$str.$version_image)) ){

            $path = $str . $version_image;
        }else{
            $path = $str . $name;
        }

        $default_image = '';
        $default_image = MediaFile::wherePath($str . $name)->first();

        if(isset($default_image) && $default_image){
            $default_image = json_encode($default_image);
        }

        $image = MediaFile::wherePath($path)->first();
        $image = json_encode($image);
    }

?>

<div id="app" class="bootstrap-classes ubold mt-5 mb-5 pl-lg-4">
    <manager-for-old-admin
        starting-image="{{ $image }}"
        default-image="{{ isset($default_image) ? $default_image : null }}"
        image-version="{{ isset($image_version) ? $image_version : null}}"
    ></manager-for-old-admin>
</div>

<script src="{{mix('js/app.js')}}"></script>
<script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>

<form method="post" id="upload_form" method="POST" action="{{ route('upload.versionImage', $event) }}" autocomplete="off" enctype="multipart/form-data">
    @csrf
    @method('put')

    @if($event)
        <div class="form-group">
            {{-- <img id="img-upload"  onerror="this.src='https://via.placeholder.com/400x250?text=PHOTO'" src="
            <?php if($event['path'] != null) {
                echo url($event['path'].$event['original_name']);
            }?>"> --}}
        </div>
        <input type="hidden" value="{{$event['path'].$event['original_name']}}" id="image_upload" name="image_upload">

        @isset($versions)
            <input type="hidden" value="{{$versions}}" name="versions">
        @endisset

    @else
        <div class="form-group">
            <img id="img-upload" src="">
        </div>
        <input type="hidden" value="" id="image_upload" name="image_upload">
    @endif
</form>

@push('js')
@endpush

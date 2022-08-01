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


    $imageedit = "false";
    if(isset($event) && $event && $event['path'] != null) {
        $imageedit = "true";
    }
?>



<div id="app1" class="bootstrap-classes ubold mt-5 mb-5 pl-lg-4 {{$from}}">
    <manager-for-old-admin-new
        imageedit="{{ $imageedit }}"
    ></manager-for-old-admin-new>
</div>


@push('js')
@endpush

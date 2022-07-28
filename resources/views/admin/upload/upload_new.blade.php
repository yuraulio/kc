<!-- <link href="{{ cdn(mix('theme/assets/css/bootstrap.css')) }}" rel="stylesheet" media="all" />
<link href="{{asset('admin_assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('admin_assets/css/saas/app-limited.css')}} " rel="stylesheet" type="text/css"/> -->

<style>

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

<script src="{{asset('js/app_new_settings_icons.js')}}"></script>


@push('js')
@endpush

<?php


    $imageedit = false;
    if(isset($event) && $event && $event['path'] != null) {
        //$imageedit = true;
    }
?>


<div id="app1" class="bootstrap-classes ubold mt-5 mb-5 pl-lg-4 {{$from}}">
    <manager-for-old-admin-new
        imageedit="{{ $imageedit }}"
    ></manager-for-old-admin-new>
</div>




@push('js')
@endpush

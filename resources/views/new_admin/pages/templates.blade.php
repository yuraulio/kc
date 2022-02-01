@extends('new_admin/layouts.vertical', ["page_title"=> "Dashboard"])

@section('css')
<!-- third party css -->

<!-- third party css end -->
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            {{-- <div class="page-title-box">
                <h4 class="page-title">Templates</h4>
            </div> --}}
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <templates></templates>

    </div>
    <!-- end row-->

</div> <!-- container -->
@endsection

@section('script')
<!-- third party js -->

<!-- third party js ends -->
@endsection

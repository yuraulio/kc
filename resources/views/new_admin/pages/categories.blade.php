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
            <div class="page-title-box mt-3 mb-3">
                <div class="page-title-right">
                    <form class="d-flex align-items-center mb-3">
                       
                        {{-- <a href="javascript: void(0);" class="btn btn-blue btn-sm ms-2">
                            <i class="mdi mdi-autorenew"></i>
                        </a> --}}
                    </form>
                </div>
                <h4 class="page-title">Categories</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <categories></categories>

    </div>
    <!-- end row-->    

</div> <!-- container -->
@endsection

@section('script')
<!-- third party js -->

<!-- third party js ends -->
@endsection
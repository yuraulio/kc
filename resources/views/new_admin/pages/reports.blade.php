@extends('new_admin/layouts.vertical', ["page_title"=> "Report Tools"])

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
                <h4 class="page-title">Report Tools</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="card-body">
                        <a href="/export" class="btn btn-secondary">Export Subscription Ends</a>
                    </div>
                </div>
            </div>
        </div>
    <!-- end row-->
    </div>
</div> <!-- container -->
@endsection

@section('script')
<!-- third party js -->

<!-- third party js ends -->
@endsection

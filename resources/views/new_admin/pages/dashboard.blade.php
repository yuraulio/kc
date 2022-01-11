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
            <div class="page-title-box">
                <div class="page-title-right">
                    <form class="d-flex align-items-center mb-3">
                        {{-- <div class="input-group input-group-sm">
                            <input type="text" class="form-control border" id="dash-daterange">
                            <span class="input-group-text bg-blue border-blue text-white">
                                <i class="mdi mdi-calendar-range"></i>
                            </span>
                        </div>
                        <a href="javascript: void(0);" class="btn btn-blue btn-sm ms-2">
                            <i class="mdi mdi-autorenew"></i>
                        </a> --}}
                        {{-- <a href="javascript: void(0);" class="btn btn-blue btn-sm ms-1">
                            <i class="mdi mdi-filter-variant"></i>
                        </a> --}}
                    </form>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        
        {{-- <dashboard-widget
            title="TOTAL USERS"
            type="users"
            icon="fe-user"
            color="primary"
            key="1"
        ></dashboard-widget> --}}

        <dashboard-widget
            title="ADMINS"
            type="admins"
        ></dashboard-widget>

        <dashboard-widget
            title="ACTIVE INSTRUCTORS"
            type="instructors"
        ></dashboard-widget>

        <dashboard-widget
            title="STUDENTS"
            type="students"
        ></dashboard-widget>

        <dashboard-widget
            title="SUCCESSFUL GRADUATES"
            type="graduates"
        ></dashboard-widget>



    </div>
    <!-- end row-->    

</div> <!-- container -->
@endsection

@section('script')
<!-- third party js -->

<!-- third party js ends -->
@endsection
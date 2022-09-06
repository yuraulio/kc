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
                <h4 class="page-title mt-3">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

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

    <div class="row">
    
        <dashboard-table
            title="COMMENTS"
            type="comments"
            description="Latest comments"
            :fields="[
                {
                    name: 'comment',
                    title: 'Comment',
                    width: '50%',
                    formatter (source) {
                        return source.length > 50 ? source.slice(0, 50 - 1) + '…' : source;
                    }
                },
                {
                    name: 'user',
                    title: 'Created by',
                    dataClass: 'text-center',
                    titleClass: 'text-center',
                    formatter (value) {
                        return value.name;
                    },
                },
                {
                    name: 'page',
                    title: 'Page',
                    dataClass: 'text-center',
                    titleClass: 'text-end',
                }
            ]"
        ></dashboard-table>

        <dashboard-table
            title="PAGES"
            type="pages"
            description="Latest pages"
            :fields="[
                {
                    name: 'title',
                    title: 'Title',
                    width: '50%',
                    formatter (source) {
                        return source.length > 30 ? source.slice(0, 30 - 1) + '…' : source;
                    }
                },
                {
                    name: 'user',
                    title: 'Created by',
                    dataClass: 'text-center',
                    titleClass: 'text-center',
                    formatter (source) {
                        return source.firstname + ' ' + source.lastname;
                    }
                },
                {
                    name: 'type',
                    title: 'Type',
                    dataClass: 'text-center',
                    titleClass: 'text-end',
                }
            ]"
        ></dashboard-table>
    
    </div>

</div> <!-- container -->
@endsection

@section('script')
<!-- third party js -->

<!-- third party js ends -->
@endsection
<!DOCTYPE html>
<html lang="en">

<head>
    @include('new_admin.layouts.shared/title-meta', ['title' => $page_title])

    @include('new_admin.layouts.shared/head-css', ["mode" => $mode ?? '', "demo" => $demo ?? ''])

</head>


<body class="loading right-bar-enabled" data-layout='{"mode": "{{$theme ?? "light" }}", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "{{$theme ?? "light" }}", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}' @yield('body-extra')>
    <div id="app">
        <!-- Begin page -->
        <div id="wrapper">

                @include('new_admin.layouts.shared/topbar')

                @include('new_admin.layouts.shared/left-sidebar')

                <!-- ============================================================== -->
                <!-- Start Page Content here -->
                <!-- ============================================================== -->

                <div class="content-page">
                    <div class="content">
                        @yield('content')
                    </div>
                    <!-- content -->

                    @include('new_admin.layouts.shared/footer')

                </div>

                <!-- ============================================================== -->
                <!-- End Page content -->
                <!-- ============================================================== -->
            </div>


        <!-- END wrapper -->

    </div>

    {{-- @include('new_admin.layouts.shared/right-sidebar') --}}

    @include('new_admin.layouts.shared/footer-script')

</body>

</html>

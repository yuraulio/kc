<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box -->
        {{-- <div class="user-box text-center">
            <img src="{{asset('admin_assets/images/users/user-9.jpg')}}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block" data-bs-toggle="dropdown">James Kennedy</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div> --}}

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul id="side-menu">

                <li>
                    <a href="{{route('admin-dashboard')}}">Dashboard</a>
                </li>

                {{-- <li class="menu-title">CMS</li> --}}

                <li>
                    <a href="#sidebarDashboards" data-bs-toggle="collapse">
                        <i data-feather="airplay"></i>
                        <span class="badge bg-success rounded-pill float-end">4</span>
                        <span> CMS </span>
                    </a>
                    <div class="collapse" id="sidebarDashboards">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin-dashboard', 'index')}}">Item 1</a>
                            </li>
                            <li>
                                <a href="{{route('admin-dashboard', 'dashboard-2')}}">Item 2</a>
                            </li>
                            <li>
                                <a href="{{route('admin-dashboard', 'dashboard-3')}}">Item 3</a>
                            </li>
                            <li>
                                <a href="{{route('admin-dashboard', 'dashboard-4')}}">Item 4</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="{{"http://" . env("APP_URL") . "/admin"}}"><span class="badge bg-secondary mt-0">Old</span> Dashboard</a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
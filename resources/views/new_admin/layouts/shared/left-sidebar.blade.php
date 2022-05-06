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

                <li class="menu-title">Overview</li>

                <li>
                    <a href="{{route('admin-dashboard')}}">
                        <i data-feather="clipboard"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="{{env("APP_URL") . "/admin"}}">
                        <i data-feather="clipboard"></i>
                        <span> <span class="badge bg-secondary mt-0 align-middle">Old</span> Dashboard </span>
                    </a>
                </li>

                <li class="menu-title">Knowledge</li>

                <li>
                    <a href="{{ env("KNOWLEDGE_URL") }}">
                        <i data-feather="book"></i>
                        <span> Knowledge pages </span>
                    </a>
                </li>

                <li class="menu-title">Management</li>

                <li {{ \Request::is('users/*') ? 'menuitem-active' : ''}}>
                    <a href="#usersCMS" data-bs-toggle="collapse">
                        <i data-feather="airplay"></i>
                        <span> Users </span>
                    </a>
                    <div class="collapse {{ \Request::is('users/*') ? ' show' : ''}}" id="usersCMS">
                        <ul class="nav-second-level">
                            <li class="{{ \Request::is('users/admins') ? 'menuitem-active' : ''}}">
                                <a href="{{route('admins-management')}}">Admins</a>
                            </li>
                            <li>
                                <a href="#">Users <span class="badge bg-primary mt-0 align-middle">Comming soon</span> </a>
                            </li>
                            <li>
                                <a href="#">Instructors <span class="badge bg-primary mt-0 align-middle">Comming soon</span> </a>
                            </li>
                            <li>
                                <a href="#">Roles <span class="badge bg-primary mt-0 align-middle">Comming soon</span> </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="{{ \Request::is('menus/*') ? 'menuitem-active' : ''}}">
                    <a href="#sidebarCMS" data-bs-toggle="collapse">
                        <i data-feather="airplay"></i>
                        <span> CMS</span>
                    </a>
                    <div class="collapse {{ \Request::is('menus/*') ? ' show' : ''}}" id="sidebarCMS">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin-categories')}}">Categories management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-templates')}}">Template management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-pages')}}">Pages management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-comments')}}">Comments management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-media')}}">Assets management</a>
                            </li>
                            <li class="{{ \Request::is('menus/*') ? 'menuitem-active' : ''}}">
                                <a href="{{route('admin-menu')}}">Menus management</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="{{route('settings')}}">
                        <i data-feather="settings"></i>
                        <span> Settings </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->

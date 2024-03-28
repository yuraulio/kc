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
                @php
                // Code to allow autologin
                $url_autologin = '';
                $user = App\Model\User::where('email', Auth::user()->email)->first();
                if($user){
                    if((string)$user->remember_token != '' && (string)$user->remember_token != null){
                        $remember_token = $user->remember_token;
                    }else{
                        $remember_token = Illuminate\Support\Str::random(60);
                        $user->remember_token = $remember_token;
                        $user->save();
                    }
                    $url_autologin = '-autologin?email='.$user->email.'&token='.$remember_token;
                }
                @endphp
                <li>
                    <a href="{{config("app.url") . "/admin" . $url_autologin}}">
                        <i data-feather="clipboard"></i>
                        <span> <span class="badge bg-secondary mt-0 align-middle">Old</span> Laravel Admin </span>
                    </a>
                </li>
              <li>
                <a href="https://admin-rose-eta.vercel.app/home/">
                  <i data-feather="clipboard"></i>
                  <span> <span class="badge bg-secondary mt-0 align-middle">New</span> React Admin </span>
                </a>
              </li>

                <li class="menu-title">Knowledge</li>

                <li>
                    <a href="{{ config("app.KNOWLEDGE_URL") }}">
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
                        <span>Website content</span>
                    </a>
                    <div class="collapse {{ \Request::is('menus/*') ? ' show' : ''}}" id="sidebarCMS">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin-categories')}}">Content categories</a>
                            </li>
                            <li>
                                <a href="{{route('admin-templates')}}">Template management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-pages')}}">Pages management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-pages-blog')}}">Blog articles management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-pages-knowledge')}}">Knowledge pages management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-comments')}}">Comments management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-media')}}">Media management</a>
                            </li>
                            <li class="{{ \Request::is('menus/*') ? 'menuitem-active' : ''}}">
                                <a href="{{route('admin-menu')}}">Menus management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-ticker')}}">Ticker management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-countdown')}}">Countdown management</a>
                            </li>
                            <li>
                                <a href="{{route('admin-reports')}}">Report Tools</a>
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
                <li>
                    <a href="{{route('royalties')}}">
                        <i data-feather="trending-up"></i>
                        <span> Royalties </span>
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

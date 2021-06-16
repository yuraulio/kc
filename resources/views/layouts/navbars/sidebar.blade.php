<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner scroll-scrollx_visible">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item {{ $parentSection == 'dashboards' ? 'active' : '' }}">
                        <a class="nav-link collapsed" href="#navbar-dashboards" data-toggle="collapse" role="button" aria-expanded="{{ $parentSection == 'dashboards' ? 'true' : '' }}" aria-controls="navbar-dashboards">
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text">{{ __('Dashboards') }}</span>
                        </a>
                        <div class="collapse {{ $parentSection == 'dashboards' ? 'show' : '' }}" id="navbar-dashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item {{ $elementName == 'dashboard' ? 'active' : '' }}">
                                    <a href="{{ route('home') }}" class="nav-link">{{ __('Dashboard') }}</a>
                                </li>

                                <!-- <li class="nav-item {{ $elementName == 'dashboard-alternative' ? 'active' : '' }}">
                                    <a href="{{ route('page.index','dashboard-alternative') }}" class="nav-link">{{ __('Alternative') }}</a>
                                </li> -->
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-accounts" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-accounts">
                            <i class="fab fa-laravel" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Accounts') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-accounts">
                            <ul class="nav nav-sm flex-column">
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item  {{ $elementName == 'role-management' ? 'active' : '' }}">
                                        <a href="{{ route('role.index') }}" class="nav-link">{{ __('Role Management') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('user.index') }}" class="nav-link">{{ __('User Management') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-menus" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-accounts">
                            <i class="fab fa-laravel" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Menus') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-menus">
                            <ul class="nav nav-sm flex-column">
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('menu.index') }}" class="nav-link">{{ __('Menus') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-sites" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-sites">
                            <i class="fab fa-laravel" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Site') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-sites">
                            <ul class="nav nav-sm flex-column">
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item  {{ $elementName == 'role-management' ? 'active' : '' }}">
                                        <a href="{{ route('notification.show') }}" class="nav-link">{{ __('Notification Messages') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('user.index') }}" class="nav-link">{{ __('Site map settings') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('user.index') }}" class="nav-link">{{ __('Sharing settings') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('user.index') }}" class="nav-link">{{ __('Search engines settings') }}</a>
                                    </li>
                                @endcan

                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('media2.index') }}" class="nav-link">{{ __('Media library') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('payments.index') }}" class="nav-link">{{ __('Payment Methods') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-pages" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-pages">
                            <i class="fab fa-laravel" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Pages') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-pages">
                            <ul class="nav nav-sm flex-column">
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item  {{ $elementName == 'role-management' ? 'active' : '' }}">
                                        <a href="{{ route('pages.index') }}" class="nav-link">{{ __('Pages') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('user.index') }}" class="nav-link">{{ __('Blog') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-booking" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-booking">
                            <i class="fab fa-laravel" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Booking') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-booking">
                            <ul class="nav nav-sm flex-column">
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item  {{ $elementName == 'role-management' ? 'active' : '' }}">
                                        <a href="{{ route('transaction.participants') }}" class="nav-link">{{ __('Participants') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-faqs" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-faqs">
                            <i class="fab fa-laravel" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Faqs') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-faqs">
                            <ul class="nav nav-sm flex-column">
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item  {{ $elementName == 'role-management' ? 'active' : '' }}">
                                        <a href="{{ route('faqs.categories') }}" class="nav-link">{{ __('Category') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('faqs.index') }}" class="nav-link">{{ __('Faqs') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-courses" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-courses">
                            <i class="fab fa-laravel" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Courses') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-courses">
                            <ul class="nav nav-sm flex-column">
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item  {{ $elementName == 'role-management' ? 'active' : '' }}">
                                        <a href="{{ route('global.index') }}" class="nav-link">{{ __('Categories') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('events.index') }}" class="nav-link">{{ __('Events') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('instructors.index') }}" class="nav-link">{{ __('Instructors') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('topics.index') }}" class="nav-link">{{ __('Topics') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('lessons.index') }}" class="nav-link">{{ __('Lessons') }}</a>
                                    </li>
                                @endcan

                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('types.index') }}" class="nav-link">{{ __('Types') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('testimonials.index') }}" class="nav-link">{{ __('Testimonials') }}</a>
                                    </li>
                                @endcan
                                {{--@can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('faqs.index') }}" class="nav-link">{{ __('Faqs') }}</a>
                                    </li>
                                @endcan--}}
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('career.index') }}" class="nav-link">{{ __('Career') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('city.index_main') }}" class="nav-link">{{ __('Cities') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('venue.index_main') }}" class="nav-link">{{ __('Venues') }}</a>
                                    </li>
                                @endcan

                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('ticket.index') }}" class="nav-link">{{ __('Tickets') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'user-management' ? 'active' : '' }}">
                                        <a href="{{ route('partner.index') }}" class="nav-link">{{ __('Partners') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'exams-management' ? 'active' : '' }}">
                                        <a href="{{ route('exams.index') }}" class="nav-link">{{ __('Exams') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users', App\Model\User::class)
                                    <li class="nav-item {{ $elementName == 'deliveries-management' ? 'active' : '' }}">
                                        <a href="{{ route('delivery.index') }}" class="nav-link">{{ __('Deliveries') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>

                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">{{ __('Documentation') }}</h6>
                <!-- Navigation -->
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/docs/getting-started/overview.html') }}" target="_blank">
                            <i class="ni ni-spaceship"></i>
                            <span class="nav-link-text">Getting started</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/docs/foundation/colors.html') }}" target="_blank">
                            <i class="ni ni-palette"></i>
                            <span class="nav-link-text">Foundation</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/docs/components/alerts.html') }}" target="_blank">
                            <i class="ni ni-ui-04"></i>
                            <span class="nav-link-text">Components</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/docs/plugins/charts.html') }}" target="_blank">
                            <i class="ni ni-chart-pie-35"></i>
                            <span class="nav-link-text">Plugins</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

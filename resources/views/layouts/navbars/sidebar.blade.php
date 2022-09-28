<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">

    <div class="scrollbar-inner scroll-scrollx_visible">
        <div class="sidenav-header d-flex align-items-center">
        <div class="logo-area">
            <a href="/" class="logo">Know Crunch</a>
        </div>
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
                    <li class="nav-item {{ isset($parentSection) and $parentSection  == 'dashboards' ? 'active' : '' }}">
                        <a class="nav-link collapsed" href="#navbar-dashboards" data-toggle="collapse" role="button" aria-expanded="{{ isset($parentSection) and $parentSection  == 'dashboards' ? 'true' : '' }}" aria-controls="navbar-dashboards">
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text">{{ __('Dashboards') }}</span>
                        </a>
                        <div class="collapse {{ isset($parentSection) and $parentSection  == 'dashboards' ? 'show' : '' }}" id="navbar-dashboards">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a href="{{env("ADMIN_URL")}}" class="nav-link">
                                        <span class="badge badge-primary">New</span> &nbsp
                                        {{ __('Dashboard') }}
                                    </a>
                                </li>

                                <li class="nav-item {{ isset($elementName) and $elementName  == 'dashboard' ? 'active' : '' }}">
                                    <a href="{{ route('home') }}" class="nav-link">{{ __('Dashboard') }}</a>
                                </li>

                                <!-- <li class="nav-item {{ isset($elementName) and $elementName  == 'dashboard-alternative' ? 'active' : '' }}">
                                    <a href="{{ route('page.index','dashboard-alternative') }}" class="nav-link">{{ __('Alternative') }}</a>
                                </li> -->
                            </ul>
                        </div>
                    </li>

                    <!-- <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-accounts" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-accounts">
                            <i class="far fa-user" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('My Account') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-accounts">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item  {{ isset($elementName) and $elementName  == 'profile-management' ? 'active' : '' }}">
                                    <a href="{{ route('user.edit', auth()->user()->id) }}" class="nav-link">{{ __('User Profile') }}</a>
                                </li>
                                <li class="nav-item {{ isset($elementName) and $elementName  == 'user-management' ? 'active' : '' }}">
                                    <a href="{{ route('user.index') }}" class="nav-link">{{ __('Users') }}</a>
                                </li>


                            </ul>
                        </div>
                    </li> -->
                    @can('manage-users', App\Model\User::class)
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-users" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-users">
                            <i class="fas fa-users" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Users') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-users">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item {{ isset($elementName) and $elementName  == 'user-management' ? 'active' : '' }}">
                                    <a href="{{ route('user.index') }}" class="nav-link">{{ __('Users') }}</a>
                                </li>

                                <li class="nav-item  {{ isset($elementName) and $elementName  == 'role-management' ? 'active' : '' }}">
                                    <a href="{{ route('role.index') }}" class="nav-link">{{ __('Roles') }}</a>
                                </li>
                                <li class="nav-item  {{ isset($elementName) and $elementName  == 'abandoned-management' ? 'active' : '' }}">
                                    <a href="{{ route('abandoned.index') }}" class="nav-link">{{ __('Abandoned') }}</a>
                                </li>


                            </ul>
                        </div>
                    </li>
                    @endcan
                    @can('manage-users', App\Model\User::class)
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-sites" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-sites">
                            <i class="fas fa-sliders-h" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Site') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-sites">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item  {{ isset($elementName) and $elementName  == 'role1-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('Notifications') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'user1-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('Site map settings') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'social-management' ? 'active' : '' }}">
                                        <a href="{{ route('social.index') }}" class="nav-link">{{ __('Social sharing settings') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'user1-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('Error log') }}</a>
                                    </li>
                                    {{--<li class="nav-item {{ isset($elementName) and $elementName  == 'media-management' ? 'active' : '' }}">
                                        <a href="{{ route('media2.index') }}" class="nav-link">{{ __('Media library') }}</a>
                                    </li>--}}
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'menu-management' ? 'active' : '' }}">
                                        <a href="{{ route('menu.index') }}" class="nav-link">{{ __('Menus') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'payment-management' ? 'active' : '' }}">
                                        <a href="{{ route('payments.index') }}" class="nav-link">{{ __('Payment Methods') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" id="update-btn" class="nav-link">{{ __('Update Dropbox') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('ads-feed')}}" class="nav-link">{{ __('Ads feed') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('lessons.novimeolink')}}" class="nav-link">{{ __('Vimeo Link Check') }}</a>
                                    </li>

                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('manage-users', App\Model\User::class)
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-pages" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-pages">
                            <i class="fas fa-columns" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Pages') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-pages">
                            <ul class="nav nav-sm flex-column">
                                    {{--<li class="nav-item  {{ isset($elementName) and $elementName  == 'pages-management' ? 'active' : '' }}">
                                        <a href="{{ route('pages.index') }}" class="nav-link">{{ __('Pages') }}</a>
                                    </li>--}}
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.index') }}" class="nav-link">{{ __('Blog posts') }}</a>
                                    </li>

                            </ul>
                        </div>
                    </li>

                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-courses" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-courses">
                            <i class="fas fa-book-reader" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Courses') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-courses">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item  {{ isset($elementName) and $elementName  == 'categories-management' ? 'active' : '' }}">
                                        <a href="{{ route('global.index') }}" class="nav-link">{{ __('Categories') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'events-management' ? 'active' : '' }}">
                                        <a href="{{ route('events.index') }}" class="nav-link">{{ __('Events') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'instructors-management' ? 'active' : '' }}">
                                        <a href="{{ route('instructors.index') }}" class="nav-link">{{ __('Instructors') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'topics-management' ? 'active' : '' }}">
                                        <a href="{{ route('topics.index') }}" class="nav-link">{{ __('Topics') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'lessons-management' ? 'active' : '' }}">
                                        <a href="{{ route('lessons.index') }}" class="nav-link">{{ __('Lessons') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'types-management' ? 'active' : '' }}">
                                        <a href="{{ route('types.index') }}" class="nav-link">{{ __('Types') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'testimonials-management' ? 'active' : '' }}">
                                        <a href="{{ route('testimonials.index') }}" class="nav-link">{{ __('Testimonials') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'careers-management' ? 'active' : '' }}">
                                        <a href="{{ route('career.index') }}" class="nav-link">{{ __('Career Paths') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'cities-management' ? 'active' : '' }}">
                                        <a href="{{ route('city.index_main') }}" class="nav-link">{{ __('Cities') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'venues-management' ? 'active' : '' }}">
                                        <a href="{{ route('venue.index_main') }}" class="nav-link">{{ __('Venues') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'tickets-management' ? 'active' : '' }}">
                                        <a href="{{ route('ticket.index') }}" class="nav-link">{{ __('Tickets') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'partners-management' ? 'active' : '' }}">
                                        <a href="{{ route('partner.index') }}" class="nav-link">{{ __('Partners') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'exams-management' ? 'active' : '' }}">
                                        <a href="{{ route('exams.index') }}" class="nav-link">{{ __('Exams') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'deliveries-management' ? 'active' : '' }}">
                                        <a href="{{ route('delivery.index') }}" class="nav-link">{{ __('Deliveries') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'videos-management' ? 'active' : '' }}">
                                        <a href="{{ route('video.index') }}" class="nav-link">{{ __('Videos') }}</a>
                                    </li>

                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'plans-management' ? 'active' : '' }}">
                                        <a href="{{ route('plans') }}" class="nav-link">{{ __('Plans') }}</a>
                                    </li>






                                <li class="nav-item active">
                                    <a class="nav-link active" href="#navbar-faqs" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-faqs">
                                        {{--<i class="far fa-question-circle" style="color: #f4645f;"></i>--}}
                                        <span class="nav-link-text" style="color: #f4645f;">{{ __('Questions & Answers') }}</span>
                                    </a>
                                    <div class="collapse" id="navbar-faqs">
                                        <ul class="nav nav-sm flex-column">
                                                <li class="nav-item  {{ isset($elementName) and $elementName  == 'faqs-category-management' ? 'active' : '' }}">
                                                    <a href="{{ route('faqs.categories') }}" class="nav-link">{{ __('Category') }}</a>
                                                </li>
                                                <li class="nav-item {{ isset($elementName) and $elementName  == 'faqs-management' ? 'active' : '' }}">
                                                    <a href="{{ route('faqs.index') }}" class="nav-link">{{ __('Questions & Answers') }}</a>
                                                </li>
                                        </ul>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('view', App\Model\Transaction::class)
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-revenue" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-revenue">
                            <i class="fas fa-euro-sign" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Sales') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-revenue">
                            <ul class="nav nav-sm flex-column">

                                    <li class="nav-item  {{ isset($elementName) and $elementName  == 'participants-management' ? 'active' : '' }}">
                                        <a href="{{ route('transaction.participants') }}" class="nav-link">{{ __('Registrations') }}</a>
                                    </li>
                                    <li class="nav-item  {{ isset($elementName) and $elementName  == 'participants-management' ? 'active' : '' }}">
                                        <a href="{{ route('transaction.participants_new') }}" class="nav-link">{{ __('Revenue') }}</a>
                                    </li>
                                    @can('manage-users', App\Model\User::class)
                                    <li class="nav-item  {{ isset($elementName) and $elementName  == 'subscriptions-management' ? 'active' : '' }}">
                                        <a href="{{ route('subscriptions.index') }}" class="nav-link">{{ __('Subscriptions') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'coupons-management' ? 'active' : '' }}">
                                        <a href="{{ route('coupons') }}" class="nav-link">{{ __('Coupons') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'discounts-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('Discounts') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'affiliates-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('Affiliates') }}</a>
                                    </li>
                                    @endcan
                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('manage-users', App\Model\User::class)
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-messages" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-messages">
                            <i class="fas fa-envelope" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Messages') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-messages">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item  {{ isset($elementName) and $elementName  == 'api-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('API Settings') }}</a>
                                    </li>
                            </ul>
                        </div>
                    </li>
                   @endcan

                </ul>

            </div>
        </div>
    </div>

</nav>

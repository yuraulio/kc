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
                                @php
                                // Code to allow autologin
                                $url_autologin = '';
                                $admin = App\Model\Admin\Admin::where('email', Auth::user()->email)->first();
                                if($admin){
                                    if((string)$admin->remember_token != '' && (string)$admin->remember_token != null){
                                        $remember_token = $admin->remember_token;
                                    }else{
                                        $remember_token = Illuminate\Support\Str::random(60);
                                        $admin->remember_token = $remember_token;
                                        $admin->save();
                                    }
                                    $url_autologin = '?email='.$admin->email.'&token='.$remember_token;
                                }
                                @endphp
                                <li class="nav-item">
                                    <a href="{{ route('admin-login').$url_autologin }}" class="nav-link">
                                        <span class="badge badge-primary">Old</span> &nbsp
                                        {{ __('Vue JS Admin') }}
                                    </a>
                                </li>

                              <li class="nav-item active">
                                <a class="nav-link active" href="https://admin-rose-eta.vercel.app/home/" target="_blank">
                                  <span class="badge badge-primary">New</span> &nbsp
                                  {{ __('React Admin') }}
                                </a>
                              </li>

{{--                                <li class="nav-item {{ isset($elementName) and $elementName  == 'dashboard' ? 'active' : '' }}">--}}
{{--                                    <a href="{{ route('home') }}" class="nav-link">{{ __('Dashboard') }}</a>--}}
{{--                                </li>--}}

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
                                        <a href="#" class="nav-link">{{ __('Notifications') }} <span class="badge bg-primary mt-0 align-middle ml-1 mt-1 text-white">Comming soon</span></a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'user1-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('Site map settings') }} <span class="badge bg-primary mt-0 align-middle ml-1 mt-1 text-white">Comming soon</span></a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'social-management' ? 'active' : '' }}">
                                        <a href="{{ route('social.index') }}" class="nav-link">{{ __('Social sharing settings') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'user1-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('Error log') }} <span class="badge bg-primary mt-0 align-middle ml-1 mt-1 text-white">Comming soon</span></a>
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
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'skills-management' ? 'active' : '' }}">
                                      <a href="{{ route('skill.index') }}" class="nav-link">{{ __('Skills') }}</a>
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

                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'giveaways-management' ? 'active' : '' }}">
                                        <a href="{{ route('giveaway.index') }}" class="nav-link">{{ __('Giveaways') }}</a>
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
                                        <a href="#" class="nav-link">{{ __('Discounts') }} <span class="badge bg-primary mt-0 align-middle ml-1 mt-1 text-white">Comming soon</span></a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName  == 'affiliates-management' ? 'active' : '' }}">
                                        <a href="#" class="nav-link">{{ __('Affiliates') }} <span class="badge bg-primary mt-0 align-middle ml-1 mt-1 text-white">Comming soon</span></a>
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
                                        <a href="#" class="nav-link">{{ __('API Settings') }} <span class="badge bg-primary mt-0 align-middle ml-1 mt-1 text-white">Comming soon</span></a>
                                    </li>
                            </ul>
                        </div>
                    </li>
                   @endcan

                   <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-messages" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-messages">
                            <i class="fas fa-envelope" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Utils') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-messages">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item {{ isset($elementName) and $elementName  == 'refresh-cache' ? 'active' : '' }}">
                                    <a href="{{ route('admin.refresh-cache') }}" class="nav-link">{{ __('Refresh cache') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                </ul>

            </div>
        </div>
    </div>

</nav>

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
                    <li class="nav-item {{ isset($parentSection) and $parentSection == 'dashboards' ? 'active' : '' }}">
                        <a class="nav-link collapsed" href="#navbar-dashboards" data-toggle="collapse" role="button" aria-expanded="{{ isset($parentSection) and $parentSection == 'dashboards' ? 'true' : '' }}" aria-controls="navbar-dashboards">
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text">{{ __('Dashboards') }}</span>
                        </a>
                        <div class="collapse {{ isset($parentSection) and $parentSection == 'dashboards' ? 'show' : '' }}" id="navbar-dashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item {{ isset($elementName) and $elementName == 'dashboard' ? 'active' : '' }}">
                                    <a href="{{ route('home') }}" class="nav-link">{{ __('Dashboard') }}</a>
                                </li>

                                <!-- <li class="nav-item {{ isset($elementName) and $elementName == 'dashboard-alternative' ? 'active' : '' }}">
                                    <a href="{{ route('page.index','dashboard-alternative') }}" class="nav-link">{{ __('Alternative') }}</a>
                                </li> -->
                            </ul>
                        </div>
                    </li>



                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-pages" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-pages">
                            <i class="fas fa-columns" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Blog') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-pages">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.index') }}" class="nav-link">{{ __('Posts') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.create_post') }}" class="nav-link">{{ __('Create Post') }}</a>
                                    </li>
                                    <hr class="mt-1 mb-1"/>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.comments.index') }}" class="nav-link">{{ __('Comments') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.comments.index') }}?waiting_for_approval=true" class="nav-link">{{ __('Waiting Approval') }}</a>
                                    </li>
                                    <hr class="mt-1 mb-1"/>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.categories.index') }}" class="nav-link">{{ __('Categories') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.categories.create_category') }}" class="nav-link">{{ __('Create Category') }}</a>
                                    </li>
                                    <hr class="mt-1 mb-1"/>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.languages.index') }}" class="nav-link">{{ __('Languages') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.languages.create_language') }}" class="nav-link">{{ __('Add Language') }}</a>
                                    </li>
                                    <hr class="mt-1 mb-1"/>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.images.all') }}" class="nav-link">{{ __('All Images') }}</a>
                                    </li>
                                    <li class="nav-item {{ isset($elementName) and $elementName == 'user2-management' ? 'active' : '' }}">
                                        <a href="{{ route('binshopsblog.admin.images.upload') }}" class="nav-link">{{ __('Upload Image') }}</a>
                                    </li>


                            </ul>
                        </div>
                    </li>
                </ul>

            </div>
        </div>
    </div>

</nav>

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
                    <li class="nav-item <?php echo e($parentSection == 'dashboards' ? 'active' : ''); ?>">
                        <a class="nav-link collapsed" href="#navbar-dashboards" data-toggle="collapse" role="button" aria-expanded="<?php echo e($parentSection == 'dashboards' ? 'true' : ''); ?>" aria-controls="navbar-dashboards">
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text"><?php echo e(__('Dashboards')); ?></span>
                        </a>
                        <div class="collapse <?php echo e($parentSection == 'dashboards' ? 'show' : ''); ?>" id="navbar-dashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item <?php echo e($elementName == 'dashboard' ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('home')); ?>" class="nav-link"><?php echo e(__('Dashboard')); ?></a>
                                </li>

                                <!-- <li class="nav-item <?php echo e($elementName == 'dashboard-alternative' ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('page.index','dashboard-alternative')); ?>" class="nav-link"><?php echo e(__('Alternative')); ?></a>
                                </li> -->
                            </ul>
                        </div>
                    </li>

                    <!-- <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-accounts" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-accounts">
                            <i class="far fa-user" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;"><?php echo e(__('My Account')); ?></span>
                        </a>
                        <div class="collapse show" id="navbar-accounts">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item  <?php echo e($elementName == 'profile-management' ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('user.edit', auth()->user()->id)); ?>" class="nav-link"><?php echo e(__('User Profile')); ?></a>
                                </li>
                                <li class="nav-item <?php echo e($elementName == 'user-management' ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('user.index')); ?>" class="nav-link"><?php echo e(__('Users')); ?></a>
                                </li>


                            </ul>
                        </div>
                    </li> -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-users" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-users">
                            <i class="fas fa-users" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;"><?php echo e(__('Users')); ?></span>
                        </a>
                        <div class="collapse show" id="navbar-users">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item <?php echo e($elementName == 'user-management' ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('user.index')); ?>" class="nav-link"><?php echo e(__('Users')); ?></a>
                                </li>

                                <li class="nav-item  <?php echo e($elementName == 'role-management' ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('role.index')); ?>" class="nav-link"><?php echo e(__('Roles')); ?></a>
                                </li>
                                <li class="nav-item  <?php echo e($elementName == 'abandoned-management' ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('abandoned.index')); ?>" class="nav-link"><?php echo e(__('Abandoned')); ?></a>
                                </li>


                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-sites" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-sites">
                            <i class="fas fa-sliders-h" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;"><?php echo e(__('Site')); ?></span>
                        </a>
                        <div class="collapse show" id="navbar-sites">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item  <?php echo e($elementName == 'role1-management' ? 'active' : ''); ?>">
                                        <a href="#" class="nav-link"><?php echo e(__('Notifications')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'user1-management' ? 'active' : ''); ?>">
                                        <a href="#" class="nav-link"><?php echo e(__('Site map settings')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'social-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('social.index')); ?>" class="nav-link"><?php echo e(__('Social sharing settings')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'user1-management' ? 'active' : ''); ?>">
                                        <a href="#" class="nav-link"><?php echo e(__('Error log')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'media-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('media2.index')); ?>" class="nav-link"><?php echo e(__('Media library')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'menu-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('menu.index')); ?>" class="nav-link"><?php echo e(__('Menus')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'payment-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('payments.index')); ?>" class="nav-link"><?php echo e(__('Payment Methods')); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" id="update-btn" class="nav-link"><?php echo e(__('Update Dropbox')); ?></a>
                                    </li>

                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-pages" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-pages">
                            <i class="fas fa-columns" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;"><?php echo e(__('Pages')); ?></span>
                        </a>
                        <div class="collapse show" id="navbar-pages">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item  <?php echo e($elementName == 'pages-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('pages.index')); ?>" class="nav-link"><?php echo e(__('Pages')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'user2-management' ? 'active' : ''); ?>">
                                        <a href="#" class="nav-link"><?php echo e(__('Blog posts')); ?></a>
                                    </li>

                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-courses" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-courses">
                            <i class="fas fa-book-reader" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;"><?php echo e(__('Courses')); ?></span>
                        </a>
                        <div class="collapse show" id="navbar-courses">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item  <?php echo e($elementName == 'categories-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('global.index')); ?>" class="nav-link"><?php echo e(__('Categories')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'events-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('events.index')); ?>" class="nav-link"><?php echo e(__('Events')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'instructors-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('instructors.index')); ?>" class="nav-link"><?php echo e(__('Instructors')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'topics-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('topics.index')); ?>" class="nav-link"><?php echo e(__('Topics')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'lessons-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('lessons.index')); ?>" class="nav-link"><?php echo e(__('Lessons')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'types-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('types.index')); ?>" class="nav-link"><?php echo e(__('Types')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'testimonials-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('testimonials.index')); ?>" class="nav-link"><?php echo e(__('Testimonials')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'careers-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('career.index')); ?>" class="nav-link"><?php echo e(__('Career Paths')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'cities-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('city.index_main')); ?>" class="nav-link"><?php echo e(__('Cities')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'venues-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('venue.index_main')); ?>" class="nav-link"><?php echo e(__('Venues')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'tickets-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('ticket.index')); ?>" class="nav-link"><?php echo e(__('Tickets')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'partners-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('partner.index')); ?>" class="nav-link"><?php echo e(__('Partners')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'exams-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('exams.index')); ?>" class="nav-link"><?php echo e(__('Exams')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'deliveries-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('delivery.index')); ?>" class="nav-link"><?php echo e(__('Deliveries')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'videos-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('video.index')); ?>" class="nav-link"><?php echo e(__('Videos')); ?></a>
                                    </li>

                                    <li class="nav-item <?php echo e($elementName == 'plans-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('plans')); ?>" class="nav-link"><?php echo e(__('Plans')); ?></a>
                                    </li>






                                <li class="nav-item active">
                                    <a class="nav-link active" href="#navbar-faqs" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-faqs">
                                        <i class="far fa-question-circle" style="color: #f4645f;"></i>
                                        <span class="nav-link-text" style="color: #f4645f;"><?php echo e(__('Questions & Answers')); ?></span>
                                    </a>
                                    <div class="collapse" id="navbar-faqs">
                                        <ul class="nav nav-sm flex-column">
                                                <li class="nav-item  <?php echo e($elementName == 'faqs-category-management' ? 'active' : ''); ?>">
                                                    <a href="<?php echo e(route('faqs.categories')); ?>" class="nav-link"><?php echo e(__('Category')); ?></a>
                                                </li>
                                                <li class="nav-item <?php echo e($elementName == 'faqs-management' ? 'active' : ''); ?>">
                                                    <a href="<?php echo e(route('faqs.index')); ?>" class="nav-link"><?php echo e(__('Questions & Answers')); ?></a>
                                                </li>
                                        </ul>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', App\Model\Transaction::class)): ?>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-revenue" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-revenue">
                            <i class="fas fa-euro-sign" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;"><?php echo e(__('Revenue')); ?></span>
                        </a>
                        <div class="collapse show" id="navbar-revenue">
                            <ul class="nav nav-sm flex-column">
                           
                                    <li class="nav-item  <?php echo e($elementName == 'participants-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('transaction.participants')); ?>" class="nav-link"><?php echo e(__('Revenue')); ?></a>
                                    </li>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
                                    <li class="nav-item  <?php echo e($elementName == 'subscriptions-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('subscriptions.index')); ?>" class="nav-link"><?php echo e(__('Subscriptions')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'coupons-management' ? 'active' : ''); ?>">
                                        <a href="<?php echo e(route('coupons')); ?>" class="nav-link"><?php echo e(__('Coupons')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'discounts-management' ? 'active' : ''); ?>">
                                        <a href="#" class="nav-link"><?php echo e(__('Discounts')); ?></a>
                                    </li>
                                    <li class="nav-item <?php echo e($elementName == 'affiliates-management' ? 'active' : ''); ?>">
                                        <a href="#" class="nav-link"><?php echo e(__('Affiliates')); ?></a>
                                    </li>
                                    <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
                    <li class="nav-item active">
                        <a class="nav-link active" href="#navbar-messages" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-messages">
                            <i class="fas fa-envelope" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;"><?php echo e(__('Messages')); ?></span>
                        </a>
                        <div class="collapse show" id="navbar-messages">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item  <?php echo e($elementName == 'api-management' ? 'active' : ''); ?>">
                                        <a href="#" class="nav-link"><?php echo e(__('API Settings')); ?></a>
                                    </li>
                            </ul>
                        </div>
                    </li>
                   <?php endif; ?>

                </ul>

            </div>
        </div>
    </div>
    
</nav>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/layouts/navbars/sidebar.blade.php ENDPATH**/ ?>
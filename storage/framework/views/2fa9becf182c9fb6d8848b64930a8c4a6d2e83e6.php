<?php if(auth()->check() && !in_array(request()->route()->getName(), ['welcome', 'page.pricing', 'page.lock'])): ?>
    <?php echo $__env->make('layouts.navbars.navs.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
    
<?php if(!auth()->check() || in_array(request()->route()->getName(), ['welcome', 'page.pricing', 'page.lock'])): ?>
    <?php echo $__env->make('layouts.navbars.navs.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/layouts/navbars/navbar.blade.php ENDPATH**/ ?>
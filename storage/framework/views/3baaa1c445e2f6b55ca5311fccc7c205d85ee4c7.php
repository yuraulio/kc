<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="background-image: url(../argon/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div style="display:block !important;" class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 <?php echo e($class ?? ''); ?>">
                <h1 class="display-2 text-white"><?php echo e($title); ?></h1>
                <?php if(isset($description) && $description): ?>
                    <p class="text-white mt-0 mb-5"><?php echo e($description); ?></p>
                <?php endif; ?>

            </div>

        </div>
        <div class="row">
            <div class="col-md-12 <?php echo e($class ?? ''); ?>">
            <?php if(isset($partner_id) || isset($kc_id)): ?>
                <div style="color: white;">
                    <?php if($partner_id != ''): ?><strong>Deree ID:</strong> <?php echo e($partner_id); ?><?php endif; ?>
                        <br/>

                    <?php if($kc_id != ''): ?><strong>KC ID:</strong> <?php echo e($kc_id); ?><?php endif; ?>
                </div>
            <?php endif; ?>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/forms/header.blade.php ENDPATH**/ ?>
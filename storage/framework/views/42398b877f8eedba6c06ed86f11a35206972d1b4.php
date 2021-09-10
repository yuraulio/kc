<?php if(session($key ?? 'status')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session($key ?? 'status')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div id="success-message" class="alert alert-success alert-dismissible success-message" style="display:none;" role="alert">
        <p> </p>
        <button type="button" class="close-message close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
</div>

<div id="error-message" class="alert alert-danger alert-dismissible error-message" style="display:none;" role="alert">
        <p> </p>
        <button type="button" class="close-message close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
</div>


<?php $__env->startPush('js'); ?>
    <script>
        $(document).on('click','.close-message',function(){
            $("#success-message").hide();
        })
    </script>
<?php $__env->stopPush(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/alerts/success.blade.php ENDPATH**/ ?>
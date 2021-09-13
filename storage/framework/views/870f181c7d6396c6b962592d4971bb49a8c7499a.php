<script>
<?php if(Session::has('opstatus')): ?>
    <?php if(Session::get('opstatus')): ?>
    notify("<?php echo Session::get('opmessage'); ?>", 'success', 8000);
    <?php else: ?>
    notify("<?php echo Session::get('opmessage'); ?>", 'error', 8000);
    <?php endif; ?>
<?php endif; ?>

<?php if(Session::has('message')): ?>

    notify("<?php echo Session::get('message'); ?>", 'success', 8000);

<?php endif; ?>

<?php if(Session::has('dperror')): ?>

    notify("<?php echo Session::get('dperror'); ?>", 'error', 8000);

<?php endif; ?>
</script>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/layouts/flash_notifications.blade.php ENDPATH**/ ?>
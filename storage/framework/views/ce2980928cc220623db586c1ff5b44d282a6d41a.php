<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="description" content="Knowcrunch - Darkpony CMS">
        <meta name="author" content="Darkpony">
        <?php echo $__env->yieldContent('head'); ?>
        <?php echo $__env->make('emails.partials.base_style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </head>
    <body>
        <header>
            <?php echo $__env->yieldContent('header'); ?>
            <?php echo $__env->make('emails.partials.email_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </header>
        <section class="emailArea">
            <?php echo $__env->yieldContent('content'); ?>
        </section>
        <footer>
            <?php echo $__env->yieldContent('footer'); ?>
            <?php echo $__env->make('emails.partials.email_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </footer>
    </body>
</html>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/emails/email_master_tpl.blade.php ENDPATH**/ ?>
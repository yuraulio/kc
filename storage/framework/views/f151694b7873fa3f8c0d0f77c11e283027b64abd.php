
<?php $__env->startSection('metas'); ?>

    <title><?php echo e($page['name']); ?></title>
   <?php echo $page->metable->getMetas(); ?>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('theme.preview.preview_warning', ["id" => $page['id'], "type" => "content", "status" => $page['status']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main id="" role="main">
   <script type="application/ld+json">
      {
          "@context": "http://schema.org",
          "@type": "WebPage",
          "name": "Logos",
          "description": "Media saying great things about us",
          "breadcrumb": "Home > Logos"
      }
   </script>
   <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
            <h1><?php echo e($page['name']); ?></h1>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
   <section class="section-page-content logos-sections">
      <div class="container">

      <?php

         $url = explode('/',request()->url());
         $url = (end($url));

         $class = 'logos-area';
         if(trim($url) == 'terms' || trim($url) == 'data-privacy-policy'){
            $class = 'logos-area terms';
         }
      ?>

         <div class="<?php echo e($class); ?> content-text-area">

             <?php echo $page['content']; ?>

             <div class="logos-area-wrapper">
            <div class="row row-flex">
               <?php if($page['id'] == 800 && isset($brands)): ?>
                  <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <?php if(isset($value['medias'])): ?>

                     <div class="col-3 col-sm-4 col-xs-6 self-align-center logo-column">
                        <a class="logo-img-wrapper" target="<?php echo e($value['target']); ?>" rel="nofollow" href="<?php echo e($value['ext_url']); ?>" title="<?php echo e($value['name']); ?>">
                        <img   alt="<?php echo e($value['name']); ?>" title="<?php echo e($value['name']); ?>" src="<?php echo e(cdn(get_image($value['medias']))); ?>" />
                        </a>
                     </div>
                     <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               <?php elseif($page['id'] == 801 && isset($logos)): ?>
                     <?php $__currentLoopData = $logos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isset($value['medias'])): ?>

                        <div class="col-3 col-sm-4 col-xs-6 self-align-center logo-column">
                           <a  class="logo-img-wrapper" target="<?php echo e($value['target']); ?>" href="<?php echo e($value['ext_url']); ?>" title="<?php echo e($value['name']); ?>">
                           <img   alt="<?php echo e($value['name']); ?>" title="<?php echo e($value['name']); ?>" src="<?php echo e(cdn(get_image($value['medias']))); ?>" />
                           </a>
                        </div>
                        <?php endif; ?>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
      <!-- /.container -->
   </section>
   <!-- /.section-page-content -->
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('theme.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/static_tpls/generic/frontend.blade.php ENDPATH**/ ?>
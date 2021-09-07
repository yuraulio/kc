<!doctype html>
<html lang="en">
<head>
<style>

    /*@font-face {
        font-family: 'Foco';
        src: url('/theme/assets/fonts/Foco_W_Lt.eot');
        src: url('/theme/assets/fonts/Foco_W_Lt.eot?#iefix') format('embedded-opentype'),
            url('/theme/assets/fonts/Foco_W_Lt.woff2') format('woff2'),
            url('/theme/assets/fonts/Foco_W_Lt.woff') format('woff'); 
    }*/


    @font-face {
        font-family: 'Foco';
        /*src: url("<?php echo e(storage_path('fonts\Foco_Lt.ttf')); ?>") format("truetype");*/
        /*src: url("<?php echo e(asset('/storage/fonts/Foco_Lt.ttf')); ?>") format("truetype");*/
        src: url("<?php echo e(asset('/fonts/Foco_Lt.ttf')); ?>") format("truetype");
        
    }



    @page  { margin: 0px; }

    .signature{
        max-width: 200px;
    }

    h1, h2 { 
       /*font-family: 'DejaVu Sans';*/
        font-family: 'Foco';

    }
            
    h2{ font-size: 20px; margin: 6px 0; padding: 0; font-weight:normal}
    .borders{
        border-left: 20px solid #3A6DA8;
        border-right: 20px solid #3A6DA8;
    }
    
    .date{
        margin-top:110px;
        color:#3A6DA8;
    }

    .logo{
        margin-top: 100px ; 
        margin-bottom: 100px; 
        text-align:center
    }

</style>
</head>
<body class="borders">
    
    <div >

        <div class="logo">
        <img src="<?php echo e(asset('theme/assets/images/certificate/knowcrunch_logo1.png')); ?>">
        </div>


        <div style="text-align:center">

            <?php if($certificate->success): ?>
                <p style="color:#3A6DA8"> awards </p>
            <?php else: ?>
                <p style="color:#3A6DA8"> certifies </p>
            <?php endif; ?>

                
            <h2> <?php echo e($certificate->user->first()->firstname); ?> <?php echo e($certificate->user->first()->lastname); ?> </h2>

            <?php if($certificate->success): ?>
                <p style="color:#3A6DA8"> with the </p>           
            <?php else: ?>
                <p style="color:#3A6DA8"> completed the </p>
            <?php endif; ?>


            <?php if($certificate->success): ?>
                <h2>Professional Diploma in Digital &amp; Social Media Marketing</h2>

            <?php else: ?>
            <h2> <?php echo e($certificate->event->first()->title); ?></h2>
            <?php endif; ?>


            <p class="date"> <?php echo e($certificate->certification_date); ?></p>
            <img class="signature" src="<?php echo e(asset('theme/assets/images/certificate/signature_aivalis.png')); ?>"> 
            <p style="font-weight:bold; color:#3A6DA8"> APOSTOLIS AIVALIS</p>
            <p style="margin-top:-10px; color:#3A6DA8"> SYLLABUS MANAGER, KNOWCRUNCH</p>
        </div>
        


    </div>
</body>
</html><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/certificates/certificate.blade.php ENDPATH**/ ?>
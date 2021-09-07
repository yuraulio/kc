
<?php $__env->startSection('content'); ?>



<table align="center" style="width:70%; font-weight:normal">
   <tr>
      <th align="left" >

      <h1> Dear <?php echo e($firstName); ?>, </h1>
        <p style="font-weight:normal">Thank you very much for all the time you spent with us and the journey we have been through together! We hope it was constructive and complete, as well as enjoyable.</p>
    
    </br>

            <p style="font-weight:normal">Attached you may find a PDF file about how you can add your certification in your Social Media, specifically LinkedIn & Facebook.</p>
                </br>
    
            <p style="font-weight:normal">Now, help us become better!
                Please take a few minutes and answer the following surveys:</p>
        </br>

        <p style="font-weight:normal">Evaluation of instructors:</br>
            <a href="https://knowcrunch.typeform.com/to/fdCkOK5O"> https://knowcrunch.typeform.com/to/fdCkOK5O </a></p>
            </br>


            <p style="font-weight:normal">Evaluation for the educational modules:</br>
            <a href="https://knowcrunch.typeform.com/to/G46Yo2hC"> https://knowcrunch.typeform.com/to/G46Yo2hC </a></p>
            </br>

        <p style="font-weight:normal">Write your testimonial:</br>
            <a href="https://knowcrunch.typeform.com/to/xT0strM9 "> https://knowcrunch.typeform.com/to/xT0strM9  </a></p>
            </br>

        

       

    
        <p style="font-weight:normal">Thank you for your help & your participation, welcome to our alumni community!</p>

        <br />
<span style="color:#006aa9;">KNOWCRUNCH </span> | LEARN. TRANSFORM. THRIVE <br />
We respect your data. Read our <a href="https://knowcrunch.com/data-privacy-policy">data privacy policy</a>

      </th>
   </tr>
  
</table>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.email_master_tpl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/emails/student/after_exam.blade.php ENDPATH**/ ?>
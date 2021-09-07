<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">

   

     @font-face {
        font-family: 'Foco';
        /*src: url("<?php echo e(storage_path('fonts\Foco_Lt.ttf')); ?>") format("truetype");*/
        src: url("<?php echo e(asset('/fonts/Foco_Lt.ttf')); ?>") format("truetype");
        
        }

        @page  {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        *{
            font-family: 'Foco';
        }
        a {
            color: #fff;
            text-decoration: none;
        }

        .user-info {
            text-align: center;
            margin-top: 100px;
            position: relative;
            /*top: 2cm;*/
            font-size: 20px;
            margin-bottom: 10px;
        }

.user-info p{   

    margin: 0;

}

td p{
    color:black;
}

        table {
            font-size: x-small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .invoice table {
            /*margin: 15px;*/
        }
        .invoice h3 {
            margin-left: 15px;
        }
        .information {
            background-color: #60A7A6;
            color: #FFF;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }

        header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                padding: 50px 70px;
                background: #3A6DA8;
                color: white;
                line-height: 1.5cm;
            }
            img {
                max-width:250px;
            }

    .invoice{
        margin-top:260px;
        padding: 0 70px;

    }

    .payment-info {
        margin-top: 100px!important;
    }
    

    footer {
                position: fixed;
                left: 0cm;
                right: 0cm;
                /*top: 100%;*/
                bottom: 0;
                height: 2cm;
                background: #3A6DA8;
                color: white;
                text-align: center;
                padding: 30px 0;
            }

            .invoice-info {  
                font-size:20px;
            }

            .date{
                color: #3A6DA8;
            }

    th{
        font-weight: normal;
    }

    .name{
        color: #3A6DA8;
    }

    .event-amount {
        color:#bbbbbb
    }

    </style>

</head>
<body>

<header>
        <img src="<?php echo e(asset('/theme/assets/images/logo-knowcrunch-seminars.svg')); ?>">
    </header>


<div class="invoice">

    <table class="invoice-info" width="100%">
        


        <tbody>
        <tr class="date">
            <td  align="left"><?php echo e($data['date']); ?></td>
            <td align="right"><?php echo e($data['invoice']); ?></td>
        </tr>
  
     
        </tbody>


     
    </table>

    <table class="user-info " width="100%">
    


     
        <tr class="date event-amount">
            <td>
                <p class="name"><?php echo e($data['name']); ?></p>
                <p> <?php echo e($data['vat']); ?> </p>
                <p><?php echo e($data['billInfo']); ?></p>
            </td>
        </tr>
  

  

     
    </table>


    <table class="invoice-info payment-info " width="100%">
        <thead style="border-bottom: 1px solid;">
        <tr>
            <th align="left">Description</th>
            <th align="right">Amount</th>
        </tr>
        </thead>


        <tbody>
        <tr class="date event-amount">
            <td  align="left"><?php echo e($data['title']); ?></td>
            <td align="right">€<?php echo e($data['amount']); ?></td>
        </tr>
  

        </tbody>

     
    </table>
</div>

<footer>
        <p> KNOWCRUNCH INC, ID: 32-055519, 651 N. BROAD ST. SUITE 206, MIDDLETOWN, DELAWARE, 19709, USA </p>
    </footer>
</body>
</html>
<thead style="border-bottom:1px solid">
      
        <?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/invoices/elearning_invoice.blade.php ENDPATH**/ ?>
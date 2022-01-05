<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">



        @page {
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

    .header-invoice{
        font-size:35px;
    }

    .details{
        margin:0;
        color:#bbbbbb
    }

    </style>

</head>
<body>

<header>
    <table class="invoice-info" width="100%">
        <tbody>
            <tr>
                <td  align="left"><img src="{{url('/theme/assets/images/logo-knowcrunch-seminars.svg')}}"></td>
                <td class="header-invoice" align="right">Invoice</td>
            </tr>
        </tbody>
    </table>
</header>


<div class="invoice">

    <table class="invoice-info" width="100%">
        {{--<thead>
        <tr>
            <th align="left">Date</th>
            <th align="right">Invoice#</th>
        </tr>
        </thead>--}}


        <tbody>
        <tr class="date">
            <td  align="left">{{$data['date']}}</td>
            <td align="right">{{$data['invoice']}}</td>
        </tr>
  
     
        </tbody>


     
    </table>

    <table class="user-info " width="100%">
    


     
        <tr class="date event-amount">
            <td>
                <p class="name">{{$data['name']}}</p>
                <p> {{$data['vat']}} </p>
                <p>{{$data['billInfo']}}</p>
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
            <td  align="left">
                <p class="details">{{$data['title']}}
                @if($data['description']), {{$data['description']}}@endif
                @if($data['installments']), payment {{$data['installments']}}</p>@endif
            </td>
            <td align="right">€{{$data['amount']}}</td>
        </tr>
  

        </tbody>

     
    </table>
</div>

<footer>
        <p> {{$data['footer']}} </p>
    </footer>
</body>
</html>
<thead style="border-bottom:1px solid">
      
        
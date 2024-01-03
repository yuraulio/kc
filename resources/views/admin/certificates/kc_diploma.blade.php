<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>

@page { margin: 0px; }

.signature{
    max-width: 200px;
}

h1, h2, h3, p {
    font-family: 'foco';
}

h2,h3{ font-size: 20px; padding: 0; font-weight:normal}

.borders{
    border-left: 44px solid #3A6DA8;
    border-right: 44px solid #3A6DA8;
}



.logo{
    margin-top:40px;
    text-align:center;
}



.knowcrunch-logo{
    max-width: 202px;
    text-align:center;
}

.knowcrunch-logo-mar{
    top:51px;
    left:24px;
}



.mar-top-50{
    margin-top: 50px;

}

.mar-top-150{
    margin-top: 170px;
    max-height:50px;
}

.name{
    color:#3A6DA8;
    font-size:48px;
    letter-spacing:3px;
}

.certificate{
    color:#3A6DA8;
    font-size:35px;
    margin:auto;
    letter-spacing:3px;
    max-width:650px;

}

h3.certificate {
    margin:0 auto -100px auto;
    line-height:0.8;
    text-align:center;
}

.award{
    font-size:20px;
    margin:-8px  auto 2px auto;
    max-width: 700px;
    line-height:1;
}


.signature-img{
    max-width:150px;

}

.knowcrunch-signature-left{
    top: 260px;
    left:130px;
}


.border-bottom{
    border-bottom: 2px solid black !important;
    max-width:295px;
    margin:10px auto 0 auto;
}

.name-signature{
    position: relative;
    font-size:20px;
    top:8px;
}

.title-signature{
    color:#000;
    font-size:17px;
    position: relative;
    top:-20px;
}



.footer{
    position: relative;
    top:160px;
    text-align: center;
    color: #666666;
    font-size:14px;

}

.mar-auto{
    margin:auto;
}

.user{
    margin-top:120px;
}

.deree-signature-rigth, .knowcrunch-signature-left{
    height:0;
}

.seperator{
    padding: 0 10px;
}


</style>
</head>
<body class="borders">
    <div class="mar-top-50">

            <div class="logo">
                <img class="knowcrunch-logo" src="{{asset('theme/assets/images/certificates2021/knowcrunch-logo.png')}}">
            </div>

        <div class="text-center user">

            <h2 class="name"> {{$certificate['firstname']}} {{$certificate['lastname']}} </h2>


            <p class="award">

                has successfully completed all exams and is hereby awarded this

            </p>


            <h3 class="certificate">  {!!mb_strtoupper($certificate['certification_title'])!!}  </h3>




        </div>

        <div class="text-center mar-top-150">
            <div class="text-center knowcrunch-signature-left">
                    <img class="signature-img" src="{{asset('theme/assets/images/certificates2021/knowcrunch-signature.png')}}">
                    <div class="border-bottom"></div>
                    <p class="name-signature"> APOSTOLIS AIVALIS</p>
                    <p class="title-signature"> Head of Curriculum, Knowcrunch</p>




            </div>
        </div>

        <div class="footer">
        <p> Issue date: {{$certificate['certification_date']}}  @if($certificate['expiration_date']) | Expiration date: {{$certificate['expiration_date']}} @endif |   Credential #: {{$certificate['credential']}} </p>
        </div>
    </div>
</body>
</html>

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

.date{
    margin-top:110px;
    color:#3A6DA8;
}

.logo{
    margin:auto;
}

.text-left{
    top:14px;
    left:373px;
}

.knowcrunch-logo{
    max-width: 202px;
}

.knowcrunch-logo-mar{
    top:51px;
    left:24px;
}

.deree-logo{
    max-width: 103px;
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
     text-align:center;;
}

.award{
    font-size:20px;
    margin:-8px  auto 2px auto;
    max-width: 700px;
    line-height:1;
}


.signature-img{
    max-width:147px;
    
}

.knowcrunch-signature-left{
    top: 160px;
    left:130px;
}

.deree-signature-rigth{
    top: 160px;
    left:550px;
}

.border-bottom{
    border-bottom: 2px solid black !important;
    max-width:295px;
    margin:10px auto 0 auto;
}

.hat-icon{
    max-width: 38px;
}

.hat-icon-mar{

    top:220px;
    left:342px;   
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

.title-signature1{
    color:#000;
    font-size:17px;
    position: relative;
    top:-50px;
}


.footer{
    position: relative;
    top:148px;
    text-align: center;
    color: #666666;
    font-size:14px;
    
}

.mar-auto{
    margin:auto;
}

.user{
    margin-top:40px;
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
        <div class="row ">
            <div class="logo col-6 knowcrunch-logo-mar">
                <img class="knowcrunch-logo  mar-auto" src="{{asset('theme/assets/images/certificates2021/knowcrunch-logo.png')}}">
            </div>

            <div class="logo col-6 text-left">
                <img class="deree-logo" src="{{asset('theme/assets/images/certificates2021/deree-logo.png')}}">
            </div>

        </div>

        <div class="text-center user">

            <h2 class="name"> {{$certificate['firstname']}} {{$certificate['lastname']}} </h2>

                
            <p class="award">
               
                has successfully completed all exams and is hereby awarded this

            </p>


            <h3 class="certificate">  {{$certificate['certification_title']}}  </h3>

           
               
           
        </div>

        <div class="row text-center mar-top-150">
            <div class="col-4 knowcrunch-signature-left">
                    <img class="signature-img" src="{{asset('theme/assets/images/certificates2021/knowcrunch-signature.png')}}">
                    <div class="border-bottom"></div>
                    <p class="name-signature"> APOSTOLIS AIVALIS</p>
                    <p class="title-signature"> Head of Curriculum, KnowCrunch</p>
                    
                </div>
                
                <div class="col-4 hat-icon-mar">
                    <img class="hat-icon" src="{{asset('theme/assets/images/certificates2021/icon.png')}}">
                </div>
                <div class="col-4 deree-signature-rigth">
                    <img class="signature-img" src="{{asset('theme/assets/images/certificates2021/deree-signature.png')}}">
                    <div class="border-bottom"></div>
                    <p class="name-signature"> ARETI KREPAPA, PHD </p>
                    <p class="title-signature"> Dean of Deree School of Graduate </p>
                    <p class="title-signature1"> and Professional Education </p>
                </div>
            </div>
        </div>
    
        <div class="footer">
            <p> Issue date: {{$certificate['certification_date']}}  @if($certificate['expiration_date']) | Expiration date: {{$certificate['expiration_date']}} @endif |   Credential #: {{$certificate['credential']}} </p>
        </div>
    </div>
</body>
</html>
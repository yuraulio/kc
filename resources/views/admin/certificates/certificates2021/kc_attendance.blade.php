<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">	
    <style>

@page { margin: 0pt; }

.signature{
    max-width: 200pt;
}

h1, h2, h3, p { 
    font-family: 'foco';
}

h2,h3{ font-size: 20pt; margin: 6pt 0; padding: 0; font-weight:normal}

.borders{
    border-left: 32.6pt solid #3A6DA8;
    border-right: 32.6pt solid #3A6DA8;
}

.date{
    margin-top:110pt;
    color:#3A6DA8;
}

.logo{
    margin:auto;
}

.text-left{left:280pt;}

.knowcrunch-logo{
    max-width: 152pt;
}

.knowcrunch-logo-mar{
    top:25pt;
    left:18pt;
}

.deree-logo{
    max-width: 77,8pt;
}

.mar-top-50{
    margin-top: 50pt;
    
}

.mar-top-150{
    margin-top: 150pt;
    max-height:50px;
}

.name{
    color:#3A6DA8;
    font-size:34pt;
    letter-spacing:3px;
}

.certificate{
    color:#3A6DA8;
    font-size:22pt;
    margin:auto;
}

.award{
    font-size:15pt;
}


.signature-img{
    max-width:120pt;
    
}

.knowcrunch-signature-left{
    top: 60pt;
    left:110pt;
}

.deree-signature-rigth{
    top: 60pt;
    left:440pt;
}

.border-bottom{
    border-bottom: 1pt solid black !important;
    padding-bottom: 25pt;
}

.hat-icon{
    max-width: 30pt;
}

.hat-icon-mar{
    left: 276pt;
    top: 100pt;
    
}

.name-signature{
    font-size:13.6pt;
    color:#000;
    position: relative;
}

.title-signature{
    color:#000;
    position: relative;
    top:-20pt;
    font-size:12pt;
}

.title-signature1{
    color:#000;
    position: relative;
    top:-40pt;
    font-size:12pt;
}


.footer{
    position: relative;
    top:30pt;
    text-align: center;
    color: grey;
}

.mar-auto{
    margin:auto;
}


.deree-signature-rigth, .knowcrunch-signature-left{
    height:0;
}

</style>
</head>
<body class="borders">
    <div class="mar-top-50">
        <div class="row ">
            <div class="logo col-6 knowcrunch-logo-mar">
                <img class="knowcrunch-logo  mar-auto" src="{{asset('certificates/knowcrunch-logo.png')}}">
            </div>

            <div class="logo col-6 text-left">
                <img class="deree-logo" src="{{asset('certificates/deree-logo.png')}}">
            </div>

        </div>

        <div class="text-center">

            <h2 class="name"> {{$certificate->user->first()->firstname}} {{$certificate->user->first()->lastname}} </h2>

                
            <p class="award">
                has attended our course Professional Diploma in Digital & Social Media Marketing </br>
                and is awarded this
            </p>


            <h3 class="certificate"> CERTIFICATE OF COMPLETION </h3>

            <div class="row mar-top-150">
                <div class="col-4 knowcrunch-signature-left">
                    <img class="signature-img" src="{{asset('certificates/knowcrunch-signature.png')}}">
                    <div class="border-bottom"></div>
                    <p class="name-signature"> APOSTOLIS AIVALIS</p>
                    <p class="title-signature"> Head of Curriculum, KnowCrunch</p>
                    
                </div>
                
                <div class="col-4 hat-icon-mar">
                    <img class="hat-icon" src="{{asset('certificates/icon.png')}}">
                </div>
                <div class="col-4 deree-signature-rigth">
                    <img class="signature-img" src="{{asset('certificates/deree-signature.png')}}">
                    <div class="border-bottom"></div>
                    <p class="name-signature"> ARETI KREPAPA, PHD </p>
                    <p class="title-signature"> Dean of Deree School of Graduate </p>
                    <p class="title-signature1"> and Professional Education </p>
                </div>
            </div>
           
        </div>
    
        <div class="footer">
            <p> Issue date: September 2021   |   Credential #: 1234567890 </p>
        </div>
    </div>
</body>
</html>
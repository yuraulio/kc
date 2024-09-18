<body class="borders">


<style>


    @page { margin: 0px; }

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
        color:#FF0000;
    }

    .logo{
        margin-top: 100px ;
        margin-bottom: 100px;
        text-align:center
    }

</style>

    <div >

        <div class="logo">
        <img src="{{asset('/theme/assets/images/certificate/knowcrunch_logo1.png')}}">
        </div>


        <div style="text-align:center">


            <p style="color:#FF0000"> certifies that </p>



            <h2> {{$certificate->user?->first()?->firstname ?? $certificate['firstname']}} {{$certificate->user?->first()?->lastname ?? $certificate['lastname']}} </h2>


            <p style="color:#3A6DA8"> successfully completed the </p>




            <h2>E-Learning Masterclass in Facebook Marketing</h2>




            <p class="date"> {{$certificate->certification_date ?? $certificate['certification_date']}}</p>
            <img class="signature" src="{{asset('theme/assets/images/certificate/signature_aivalis.png')}}">
            <p style="font-weight:bold; color:#3A6DA8"> APOSTOLIS AIVALIS</p>
            <p style="margin-top:-10px; color:#3A6DA8"> SYLLABUS MANAGER, KNOWCRUNCH</p>
        </div>



    </div>
</body>

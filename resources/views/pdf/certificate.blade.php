<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Certificate</title>
  <style>
    .wrapper{
        width: 100%;
        display: block;
        background-image: url("{{url('ccf.png')}}");
        background-position: center;
        background-size: 100% 100%;
        background-repeat: no-repeat;
        height: 842px;
    }

    .additional-div {
        margin: 0 auto;
        width: 465px;
        height: 270px;
        position: absolute;
        top: 410px;
        left: 128px;
        background: #ffffff;
    }

    .heading {
        text-align: center;
    }

    .heading h1 {
        font-size: 65px;
        font-weight: 400;
        /*color: #A0C7CD;*/
        margin: 0px;
    }

    .parent-div {
        width: 60%;
        margin: 0 auto;
        border-bottom: 1px solid #000000;
        padding-top: 30px;
    }

    .name-div {
        margin: 2px;
        background-color: #CFE3E6;
        text-align: center;
    }

    .name-div p {
        margin: 0px;
        color: #111;
        padding: 10px;
        font-size: 16px;
    }

    .date-div {
        text-align: center;
    }
    .date-div p {
        padding-top: 10px;
        font-size: 14px;
        margin: 0px;
    }

    .signature-div {
        width: 75%;
        margin: 0 auto;
        display: block;
        overflow: hidden;
        padding-bottom: 15px;
    }

    .left-child {
        width: 45%;
        float: left;
        min-height: 150px;
        border-bottom: 2px solid #949494;
        position: relative;
    }
    .right-child {
        width: 45%;
        float: right;
        min-height: 150px;
        border-bottom: 2px solid #949494;
        position: relative;
    }

    .vb {
        position: absolute;
        bottom: 50px;
        left: 0px;
    }

    .left {
        width: 45%;
        float: left;
    }

    .right {
        width: 45%;
        float: right;
        text-align: center;
    }

    .fs-16 {
        font-size: 16px;
        margin: 0px;
        padding-top: 10px;
        text-align: center;
    }
h1 {
font-size: 72px;
background: -webkit-linear-gradient(#eee, #333);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
}
.text {
text-transform: uppercase;
background: linear-gradient(to right, #30CFD0 0%, #330867 100%);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
font: {
size: 20vw;
family: $font;
};
}
  </style>
</head>
<body>
    
    <div class="wrapper">
        <div class="additional-div">

            @if($rank==1)
                <div class="heading">
                    <h1 style="color: #FACC34">WINNER {{ \Carbon\Carbon::now()->year }}</h1>
                </div>
            @elseif($rank==2)
                <div class="heading">
                    <h1 style="color: #CABBBB;">2. PLATZ {{ \Carbon\Carbon::now()->year }}</h1>
                </div>
            @elseif($rank==3)
                <div class="heading">
                    <h1 style="color: #72AEB5">3. PLATZ {{ \Carbon\Carbon::now()->year }}</h1>
                </div>
            @else
                <div class="heading">
                    <h1 style="color: #72AEB5">Teilgenommen</h1>
                </div>
            @endif

 
            <div class="parent-div">
                <div class="name-div">
                    <p>{{$firma}}</p>
                </div>
                <div class="name-div">
                    <p>{{ $category }}</p>
                </div>
            </div>
            <div class="date-div">
                <p>Wien, {{ \Carbon\Carbon::now()->day }}. {{ \Carbon\Carbon::now()->month }} {{ \Carbon\Carbon::now()->year }}</p>
            </div>
            <div class="signature-div">
                <div class="left-child">
                    <p class="vb"></p>
                </div>
                <div class="right-child">
                    <p class="vb"></p>
                </div>
{{--                 <div class="left">
                    <p class="fs-16">Susanne Hummel</p>
                </div>
                <div class="right">
                    <p class="fs-16">Bianca Lehrner</p>
                </div> --}}
            </div>

        </div>
    </div>
{{--   <div>

  </div> --}}
</body>
</html>

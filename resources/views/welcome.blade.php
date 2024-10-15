<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Austrian Wedding Award</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .home-msg {
            color: #636b6f;
            font-weight: 500;
            font: caption;
        }
    </style>

    <script>
        // Set the date we're counting down to
        var countDownDate = new Date("Oct 1, 2021 20:20:20").getTime();

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            const element = document.getElementById("demo")
            if (element) {
                element.innerHTML = days + "d " + hours + "h " +
                    minutes + "m " + seconds + "s ";
            }

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                if (element) {
                    element.innerHTML = "EXPIRED";
                }
            }
        }, 1000);
    </script>

</head>

<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Einloggen</a>
                <a href="{{ route('register') }}">Registrieren</a>
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Austrian Wedding Award {{ \Carbon\Carbon::now()->addYear(1)->year }}
        </div>
        <br>
        <h4>Die Einreichungen zum Austrian Wedding Award sind kostenpflichtig!</h4><br><br>
        <div class="home-msg">
            Die Kosten für die erste Einreichung beträgt <strong> € 120,- inkl. Ust.</strong><br>
            Darauffolgende Einreichungen kosten <strong>€ 48,- inkl. Ust.</strong><br><br>
            Wir weisen darauf hin, dass alle Projekte, die bis 15. November {{ \Carbon\Carbon::now()->year }}<br>
            hochgeladen werden, auch verrechnet werden.<br><br>
            Für eine Bearbeitungsgebühr von <strong>€ 18,- inkl. Ust.</strong> kümmern<br>
            wir uns um deinen Upload.<br>
            {{-- Alle Details findest du hier >> <a
                href="https://austrianweddingaward.at/einreichung-beim-austrian-wedding-award/#">Teilnahmebedingungen</a><br> --}}

        </div>
    </div>
</div>
</body>

</html>

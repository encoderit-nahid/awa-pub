<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('additional-styles')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style type="text/css">
        .navbar-laravel, body {
            background-color: #fff;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }
        .logo {
            width: 150px;
            margin-top: -20px;
        }
        .message-container {
            margin: 0 auto !important;
            text-align: center !important;
            align-content: center;
            align-items: center;
        }
        .sub-msg {
            font-size: 13px; 
        }
    </style>
</head>

<body>
    @yield('cron-message')
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div style="width: 100%" class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('vote-login') }}">{{ __('Einloggen') }}</a></li>
                            <li><a class="nav-link" href="{{ route('vote-register') }}">{{ __('Registrieren') }}</a></li>
                            <li><a class="nav-link" href="{{ route('contact') }}">{{ __('Kontakt') }}</a></li>
                        @else
                            @if ($loggedUser->rolle == 9)
                                <li><a class="nav-link" href="{{ route('vote-score') }}">{{ __('Vote Score') }}</a></li>
                            @endif
                            <li><a class="nav-link" href="{{ route('votes') }}">{{ __('Voting') }}</a></li>
                            <li><a class="nav-link" href="{{ route('contact') }}">{{ __('Kontakt') }}</a></li>
                            <li>
                                <a class="nav-link" href="{{ route('vote-logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('vote-logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                            
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div style="text-align: center">
            <a href="{{ url('/') }}" class="logo-link"><img class="logo rounded mx-auto" src="{{ asset('AWALogoGold_2023.jpg') }}"></a>
        </div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('additional-js')
</body>

</html>

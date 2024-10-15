@extends('layouts.app')
@section('additional-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous" />

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .content {
            /* margin-top: 100px; */
            text-align: center;
            padding: 20px;
            background-color: #f55442;
            color: #fff;
            width: 500px;
            margin: 100px auto;
        }
    </style>
@endsection
@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <h1>Ihre Zahlung wurde storniert</h1>
            @if ($error)
                <h4>{{ $error }}</h4>
            @endif
            <br><br>
            <a href="{{ route('home') }}" class="btn btn-success">Gehen Sie zur Startseite</a>
        </div>
    </div>
@endsection

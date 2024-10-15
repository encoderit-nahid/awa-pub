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
            margin-top: 100px;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="flex-center position-ref full-height">

        <div class="content">
            @if ($project_payment >= 1)
                {{-- <h1>You have to pay $40 for porject registration</h1> --}}
                <h1>Sie müssen $40 für die Projektregistrierung bezahlen</h1>
            @else
                <h1>Sie müssen $96 für die Projektregistrierung bezahlen</h1>
            @endif

            <table border="0" cellpadding="10" cellspacing="0" align="center">
                <tr>
                    <td align="center"></td>
                </tr>
                <tr>
                    <td align="center"><a href="https://www.paypal.com/in/webapps/mpp/paypal-popup" title="How PayPal Works"
                            onclick="javascript:window.open('https://www.paypal.com/in/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img
                                src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-200px.png" border="0"
                                alt="PayPal Logo"></a></td>
                </tr>
            </table>
            @if ($project_payment >= 1)
                <a href="{{ route('payment', 40) }}" class="btn btn-success">Pay $40 from Paypal</a>
            @else
                <a href="{{ route('payment', 96) }}" class="btn btn-success">Pay $96 from Paypal</a>
            @endif

        </div>
    </div>
@endsection

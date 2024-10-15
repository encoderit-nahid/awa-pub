@component('mail::message')
    Hello {{ $user_name }},
    <br />
    Dein Projekt <b>{{ $project_name }}</b> weist leider Mängel auf und wurde deshalb zurückgewiesen. <br />
    Du hast die Möglichkeit den Fehler in deinem Loginbereich zu korrigieren:
    <br />
    <h2>Grund:</h2>
    {!! $email_body !!}
    
    {!! nl2br('Vielen Dank!') !!}
    {!! nl2br('Dein AWA - Team') !!}
@endcomponent

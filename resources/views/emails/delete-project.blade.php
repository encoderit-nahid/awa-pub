@component('mail::message')
    Hallo {{ $user_name }},
    <br />
    Dein Projekt <b>{{ $project_name }}</b> wurde gelöscht!
    <br />
    <br />
    Vielen Dank,<br />
    {{ config('app.name') }}
@endcomponent

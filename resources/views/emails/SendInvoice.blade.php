@component('mail::message')
    {!! nl2br($body) !!}

    {!! nl2br(config('app.name')) !!}
@endcomponent

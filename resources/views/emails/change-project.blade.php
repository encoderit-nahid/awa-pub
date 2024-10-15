@component('mail::message')
    Hallo {{ $user_name }},
    <br />
    Dein Projekt <b>{{ $project_name }}</b> wurde geändert.<br />
    Du hast bis <b>15.11.</b> die Möglichkeit daran noch Änderungen vorzunehmen.<br />
    <br />
    Vielen Dank,<br />
    Dein AWA - Team
@endcomponent

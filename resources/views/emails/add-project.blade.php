@component('mail::message')
    Hallo {{ $user_name }},
    <br />
    Herzliche Gratulation!!<br />
    Dein Projekt <b>{{ $project_name }}</b> wurde erfolgreich eingereicht.<br /><br />
    Du hast bis zum <b>15.11.</b> die Möglichkeit daran noch Änderungen vorzunehmen. Jedes eingereichte Projekt wird
    verrechnet. Die Rechnung wird <b>im Jänner</b> verschickt. <br /><br />
    Vielen Dank, <br />
    Dein AWA - Team
@endcomponent

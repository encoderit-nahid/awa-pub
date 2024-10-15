@component('mail::message')
    Hey {{ $user_name }},
    <br />
    Du bist nun einen Schritt weiter. Dein Projekt <b>{{ $project_name }}</b> wurde zur Bewertung freigegeben.<br />
    Wir verständigen dich Anfang Jänner, ob du es ins Finale geschafft hast.<br />
    Es bleibt spannend.<br /><br />
    Vielen Dank,<br />
    Dein AWA - Team
@endcomponent

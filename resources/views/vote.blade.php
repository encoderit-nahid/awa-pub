@extends('layouts.vote-app')

@section('content')

@if(session()->has('alert-success'))
    <div class="alert alert-success">
        {{ session()->get('alert-success') }}
    </div>
@endif
<style type="text/css">
</style>
<input type = "hidden" name = "ajax_token" value = "{{csrf_token()}}">
    <div class="message-container mt-4 pt-4">
        <h3>ONLINE VOTING KATEGORIE STYLED SHOOT TEAM 2025</h3>
        @if ($displayPublicVote)
            <div class="sub-msg col-sm-8 offset-sm-2"><br/>
                Willkommen beim Austrian Wedding Award!<br/><br/>

Der AWA ist eine Auszeichnung, die an herausragende Dienstleister der österreichischen Hochzeitsbranche verliehen wird. Eingereichte Projekte werden in 33 Kategorien von unserer Experten-Jury bewertet. In der Kategorie „Styled Shoot Team“ allerdings, zählt zusätzlich zur Jurybewertung, das Publikumsvoting. <br/><br/>

Und damit zählt auch deine Stimme!<br/><br/>

Wie funktioniert es?<br/>
Bitte registriere dich mit deiner E-Mail-Adresse.<br/>
Im Menüpunkt „Voting“ findest du die 10 besten Styled Shoots des AWAs 2025
Verschaffe dir zuerst einen Überblick. Mit Klick auf die Vorschau-Fotos, kannst du alle Fotos in vergrößerter Darstellung ansehen. Vergib für jedes Projekt Punkte zwischen 0 und 10, 10 ist die beste Bewertung. Sobald du für ein Projekt abgestimmt hast, verschwindet es aus deiner Ansicht.<br/><br/>

Wir danken dir schon jetzt für‘s Mitmachen. 




            </div>
        @else
            <div class="sub-msg col-sm-8 offset-sm-2"><br/>
                <h1><b>ABGESCHLOSSEN</b></h1><br/><br/>
            </div>
        @endif
    </div>
@endsection

@extends('layouts.app')

@section('content')
    @if (session()->has('alert-success'))
        <div class="alert alert-success">
            {{ session()->get('alert-success') }}
        </div>
    @endif

    <!-- voting == 1 bedeutet, man kann voten, insert == 1 bedeutet man kann Projekte anlegen! insert == 1 wird beim anlegen des Users automatisch gesetzt!
                                                                                                                                                                                                                                                                                         voting == 0 bedeutet, das Voting ist beendet. Gilt für Rolle = 1, Rolle = 2, Rolle = 5
                                                                                                                                                                                                                                                                                         voting == 1 bedeutet, dass alle Voten können.
                                                                                                                                                                                                                                                                                    -->

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <!--   <a href="{{ url('/award-upload-by-user-list') }}">Liste jener Projekte die bereits für das AWABooklet hochgeladen wurden </a> -->
                        @if ($user->is_upload_award == 1 || $user->rolle == 9)
                            <a href="{{ url('/award-upload-by-user') }}">Upload für das AWABooklet</a>

                            <br>
                        @endif

                        @if ($user->rolle == 0)
                            @if ($user->insert == 0)
                                <!--  <script type="text/javascript">
                                    alert(
                                        "Momentan gibt es Probleme bei der\nAnzeige der hochgeladenen Videos!\nWir arbeiten an der Lösung des Problems!\nWenn Du bei Deinem Projekt einen Vimeo Link siehst, dann\nwurde Dein Video korrekt bei Vimeo hochgeladen!"
                                    );
                                </script> -->
                                <ul>
                                    <li>Bitte ergänze und aktualisiere deine Firmendaten (Instagramprofil,
                                        Rechnungsadresse...)</li>
                                    <li>Bitte denk daran, die Projekte <strong>anonymisiert</strong> einzureichen.</li>
                                    <li>Sobald Du ein Projekt angelegt und gespeichert hast, werden die Daten auch dem
                                        Award-Team sichtbar.</li>
                                    <li>Du kannst Deine Projekte bis zum <strong>15.11.2022</strong>
                                        ändern/korrigieren/ergänzen.</li>
                                    <li>Das Copyright für die Fotos ist ein absolutes Pflichtfeld (DSGVO) - das Copyright
                                        wird der Jury nicht angezeigt!</li>
                                    <li>Ein finales Abschicken oder Einreichen des Projektes ist nicht erforderlich. </li>
                                    <li>Nach dem <strong>15.11.2022</strong> erfolgt die Kontrolle und Freigabe an die Jury.
                                        Sobald euer Projekt freigegeben wurde, ändert sich der Status von
                                        <strong>„abgespeichert“ zu „freigeben“</strong>. Diese Kontrolle kann bis zu 3
                                        Wochen dauern.
                                    </li>
                                    <li>Wir wünschen dir viel Erfolg!</li><br>
                                    <p>Susanne Hummel & Bianca Lehrner</p>
                                    <li>Die Rechnung wird Dir nach Freigabe des Projektes via E-Mail zugeschickt!<br>
                                        <br>
                                        Technischer Support: office@austrianweddingaward.at
                                    </li>
                                </ul>
                            @elseif ($user->insert == 1)
                                <!--    <script type="text/javascript">
                                    alert(
                                        "Momentan gibt es Probleme bei der\nAnzeige der hochgeladenen Videos!\nWir arbeiten an der Lösung des Problems!\nWenn Du bei Deinem Projekt einen Vimeo Link siehst, dann\nwurde Dein Video korrekt bei Vimeo hochgeladen!"
                                    );
                                </script> -->
                                <br>
                            @endif
                        @elseif ($user->rolle == 5)
                            @if ($user->insert == 0)
                                @if ($user->voting == 1)
                                    Liebe/r {{ $user->vorname }}, <br><br>

                                    Die Einreichung für den Austrian Wedding Award ist abgeschlossen. Es dürfen keine
                                    Projekte hochgeladen oder verändert werden! <br><br>

                                    Wie geht es weiter? Wir überprüfen nun die Einreichungen. Sobald dieser Punkt
                                    abgeschlossen ist, wird die Jury mit der Bewertung der Projekte starten. <br><br>

                                    Eure Projekte sollten den Status "abgespeichert" aufweisen, wenn dem so ist, so ist
                                    alles in Ordnung und die Projekte werden von uns überprüft!<br><br>

                                    Die Rechnungen senden wir zu, sobald wir mit der Überprüfung der Projekte fertig
                                    sind!<br><br>

                                    Du gehörst zum Club of Excellence und darfst mitvoten. Wir haben für Dich die Kategorie
                                    Innovation geöffnet!<br><br>
                                    In der Kategorie Innovation befinden sich 22 Projekte! <br><br>
                                    Ein paar Tipps zur Bewertung.<br><br>

                                    1) Du solltest die Arbeiten der Kategorie Innovation am gleichen Tag bewerten<br>
                                    2) Verschaff Dir zuerst einen Überblick. Dazu scroll bitte ganz nach unten und lade die
                                    Bilder vollständig.
                                    Erst wenn Du den Text „no more projects“ seht, ist die Kategorie vollständig geladen<br>
                                    3) Zur genaueren Betrachtung der Bilder, klick auf das Foto – eine größere Anzeige wird
                                    geöffnet.<br>
                                    5) Bewerte die Projekte mit einer Punktevergabe von 0-100. 100 ist die Bestzahl.
                                    <br>Soabald Du einen Punktewert ausgewählt hast,
                                    wurde das Projekt bewertet und es verschwindet aus der Übersicht.<br><br>
                                @elseif ($user->voting == 0)
                                    Das Voting ist abgeschlossen - es können keine Projekte mehr beworben werden!<br>
                                    <br>
                                    Wir freuen uns auf Eure Teilnahme beim Austrian Wedding Award!<br>
                                @endif
                            @elseif ($user->insert == 1)
                                Du bist Mitglied des COE! <br>
                                Wir freuen uns auf Deine Teilnahme beim Austrian Wedding Award!<br>
                            @endif
                        @elseif ($user->rolle == 1)
                            @if ($user->voting == 1)
                                Liebe/r {{ $user->vorname }}, <br><br>
                                In den letzten Wochen haben wir alle Projekte kontrolliert, auf Kategoriekriterien überprüft
                                & nun für euch freigegeben.<br>
                                Die Bewertung erfolgt nun in 2 Phasen:<br><br>
                                {{-- Ein paar Tipps zur Bewertung.<br><br>
                                1) Bitte bewertet eine Kategorie nach der anderen<br>
                                2) Ihr solltet die Arbeiten einer Kategorie am gleichen Tag bewerten<br>
                                3) Verschafft euch zuerst einen Überblick. Dazu scrollt bitte ganz nach unten und ladet die
                                Bilder vollständig.
                                Erst wenn ihr den Text „no more projects“ seht, ist die Kategorie vollständig geladen<br>
                                4) Zur genaueren Betrachtung der Bilder, klickt auf das Foto – eine größere Anzeige wird
                                geöffnet. Möchtet ihr es noch genauer sehen, könnt ihr das Bild mit der rechten Maustaste in
                                einem neuen Tab öffnen<br>
                                5) Bewertet die Projekte mit einer Punktevergabe von 0-100. 100 ist die Bestzahl.
                                <br>Soabald ihr einen Punktewert ausgewählt habt,
                                wurde das Projekt bewertet und es verschwindet aus der Übersicht.<br>
                                6) Bitte bewertet ALLE Kategorien und Projekte, damit jeder Teilnehmer eine faire und
                                gleiche Chance bekommt.<br><br>

                                Bewertet wird in erster Linie Kreativität und Originalität. Lest dazu bitte auch diverse
                                Texte. --}}
                                <b>Bewertungsrunde 1</b><br>
                                Jede/r von euch hat nun in der ersten Bewertungsrunde nur einen Teil der Kategorien zu
                                sichten.
                                Bitte entscheide in der ersten Runde, ob dich das Projekt begeistert, sodass es in die
                                2. Bewertungsrunde geschickt wird.<br><br>
                                <b>Bewertungsrunde 2</b><br>
                                Sobald alle Juroren die erste Bewertungsrunde durch haben, wird die 2. Bewertungsrunde
                                von uns freigeschalten.<br />

                                Hier bewerten alle Juroren alle Projekte – aufgrund eurer Vorauswahl wird sich die
                                Anzahl massiv reduzieren. Somit bleibt euch mehr Zeit & Aufmerksamkeit für die
                                verbliebenen Projekte.<br /><br />
                                Bewertet bitte die Kreativität & Originalität der Projekte.<br /><br />
                                1)Bitte bewertet eine Kategorie nach der anderen<br />
                                2) Ihr solltet die Arbeiten einer Kategorie am gleichen Tag bewerten<br />
                                3) Verschafft euch zuerst einen Überblick. Dazu scrollt bitte ganz nach unten und ladet
                                die Bilder vollständig. Erst wenn ihr den Text „no more projects“ seht, ist die
                                Kategorie vollständig geladen<br />
                                4) Zur genaueren Betrachtung der Bilder, klickt auf das Foto – eine größere Anzeige wird
                                geöffnet. Möchtet ihr es noch genauer sehen, könnt ihr das Bild mit der rechten
                                Maustaste in einem neuen Tab öffnen<br />
                                5) Bewertet die Projekte mit einer Punktevergabe von 0-100. 100 ist die
                                Bestzahl.<br /><br />
                                Bewertungshilfestellung:<br />
                                • 100 Punkte: außergewöhnlich, beeindruckend<br />
                                • 80-90 Punkte: sehr schön, bleibt mir in Erinnerung<br />
                                • 60-70 Punkte: schon sehr viel Schönes/Gutes dabei<br />
                                • 40-50 Punkte: solide Arbeit<br />
                                • 20-30 Punkte: o.k.<br />
                                • 10 Punkte: nicht bewertungswürdig (Arbeit schlecht, Bewerbung schlecht, lieblos) –
                                diese Arbeiten sollten eigentlich gar nicht mehr in der 2. Bewertungsrunde
                                aufscheinen<br /><br />
                                6) Sobald ihr einen Punktewert ausgewählt habt, wurde das Projekt bewertet und es
                                verschwindet aus der Übersicht. Es kann nicht mehr zurückgeholt werden<br />
                                7) Bitte bewertet ALLE Kategorien und Projekte, damit jeder Teilnehmer eine faire und
                                gleiche Chance bekommt.<br /><br />

                                Bewertet wird in erster Linie Kreativität und Originalität. Lest dazu bitte auch diverse
                                Texte.<br />
                                Vielen Dank für euren Einsatz!<br />
                                Und jetzt viel Spaß<br />
                                Susanne & das AWA Team<br />
                                P.S.: Solltet ihr Fragen haben, meldet euch auch gerne telefonisch bei mir: 0043 699
                                1000 86 55 oder per Mail: office@austrianweddingaward.at
                                <br><br>
                            @elseif ($user->voting == 0)
                                Das Voting startet bald!
                            @endif
                        @elseif ($user->rolle == 9)
                        @endif
                        @if ($user->rolle == 0)
                            {{ $user->vorname }}, Du bist eingeloggt!
                            <br><br>
                            WICHTIG: Bitte ergänze und aktualisiere deine Firmendaten (Social Media Profil,
                            Rechnungsadresse...)<br><br>
                            Achte bei deinen Einreichungen genau auf die Kategoriekriterien.<br><br>
                            Wir wünschen dir viel Erfolg!<br><br>
                            Susanne Hummel & das AWA Team
                        @endif
                        @if ($user->teilnahmebedingung == 0)
                            <script type="text/javascript">
                                window.location = "add-teilnahmebedingung"; //here double curly bracket
                            </script>
                        @endif

                        @if ($user->first == 0)

                            <script type="text/javascript">
                                window.location = "user-change"; //here double curly bracket
                            </script>
                        @else
                            <div class="links">

                                @if ($user->rolle == 0)
                                    @if ($user->voting == 1)
                                        <a href="{{ route('project-insert') }}">{{ __('Projekt anlegen') }}</a><br>
                                    @endif
                                    <a href="{{ route('project-show') }}">{{ __('Projekt(e) anschauen') }}</a>
                                @elseif ($user->rolle == 1 || $user->rolle == 2)
                                    @if ($user->voting == 1)
                                        <a style="display: {{ $displayPermission ? 'none' : 'inline-block' }}"
                                            href="{{ route('project-show-1st-round') }}">{{ __('1. Bewertungsrunde') }}</a>
                                        <a style="display: {{ $displayPermission ? 'inline-block' : 'none' }}"
                                            href="{{ route('project-freigegebene') }}">{{ __('2. Bewertungsrunde') }}</a>
                                        |
                                        <a href="{{ route('project-show-rater') }}">{{ __('Projekt(e) bewerten') }}</a>
                                    @endif
                                @elseif ($user->rolle == 9)
                                    <a href="{{ route('project-freigeben') }}">{{ __('Projekt(e) freigeben') }}</a>
                                @endif
                            </div>
                        @endif
                            <a href="{{ route('project-freigeben') }}">{{ __('Projekt(e) freigeben') }}</a>
                        <!-- Rejected Projects table data  -->
                        </br></br>
                        @if (count($projects) > 0)
                            <h2>Folgende Projekte weisen Fehler auf:</h2><br>
                            <p>Bitte auf das Projekt klicken um den Fehler zu beheben:</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Project ID </th>
                                        <th>Project Name</th>
                                        <th>Mangel</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php //echo'<pre>'; print_r($projects);
                                    ?>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td><a
                                                    href="{{ url('project/single/admin/' . $project->id) }}">{{ $project->id }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ url('project/single/admin/' . $project->id) }}">{{ $project->projektname }}</a>
                                            </td>
                                            <td>{{ $project->reject_text }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('project-change') }}">
                                                    @csrf
                                                    {{ Form::hidden('projectID', $project->id) }}
                                                    {{ Form::hidden('catID', $project->cat_id) }}
                                                    <button type="submit" class="btn btn-primary" value="change"
                                                        name="submit">
                                                        {{ __('Ändern') }}
                                                    </button>
                                                    <a href="{{ url('/project/add-image/' . $project->id . '/' . $project->cat_id) }}"
                                                        class="btn btn-primary" disabled>Bild hinzufügen</a>
                                                    <a href="{{ url('/project/edit-image/' . $project->id . '/' . $project->cat_id) }}"
                                                        class="btn btn-primary" disabled>Bild(er) ändern</a>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        <!-- Rejected Projects table data  -->

                        {{-- @if (count($projects) > 0)
                            <h2>Awards:</h2><br>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Badge</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($awards as $award)
                                        <tr>
                                            <td>
                                                {{ $award->name }}
                                            </td>
                                            <td>
                                                <img src="{{ url('storage/' . $award->image) }}" alt=""
                                                    style="width: 100px;">
                                            </td>
                                            <td>
                                                <a href="{{ url('storage/' . $award->image) }}" download>
                                                    <button class="btn btn-primary">Download</button></a>
                                                <form action="/upload-image" method="POST" enctype='multipart/form-data'>
                                                    <div class="d-flex my-4">
                                                        @csrf
                                                        <input type="hidden" name='award_id' value="{{ $award->id }}">
                                                        <input type="file" class="btn btn-default" name='image_path[]'
                                                            multiple accept="image/*">
                                                        <input type="submit" value="Upload Image"
                                                            class="ml-2 btn btn-primary">
                                                    </div>
                                                </form>
                                                <button class="btn btn-primary share-btn" data-toggle="tooltip"
                                                    data-placement="top" title="Click the button to copy link!!"
                                                    data-id="{{ $award->id }}">Share Gallery</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on('click', '.share-btn', function() {
                var $this = $(this)
                let id = $this.data("id");
                $.ajax({
                    url: "{{ route('shareurl') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        var sampleTextarea = document.createElement("textarea");
                        document.body.appendChild(sampleTextarea);
                        sampleTextarea.value = data.url; //save main text in it
                        sampleTextarea.select(); //select textarea contenrs
                        document.execCommand("copy");
                        document.body.removeChild(sampleTextarea);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
    </script>
@endsection

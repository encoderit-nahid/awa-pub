@extends('layouts.app')

@section('content')

    @if (session()->has('alert-success'))
        <div class="alert alert-success">
            {{ session()->get('alert-success') }}
        </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">So funktioniert's: Der Bedienungshinweis für die Einreichung zum AWA</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            <p>
                                @endif

                                {{ $user->vorname }}, bitte genau lesen... </p>
                            <h5>EINREICHUNGEN</h5>
                            <ul>
                                <li>Bitte denk daran, die Projekte anonymisiert einzureichen.</li>
                                <li>Sobald du ein Projekt angelegt und gespeichert hast, werden die Daten auch dem Award-Team
                                    sichtbar.
                                </li>
                                <li>Du kannst deine Projekte bis zum 15.11. ändern/korrigieren/ergänzen/löschen.</li>
                                <li>Nicht gelöschte oder fehlerhafte Projekte werden verrechnet.</li>
                                <li>Das Copyright für die Fotos ist ein absolutes Pflichtfeld (DSGVO) - das Copyright wird der
                                    Jury nicht angezeigt!
                                </li>
                                <li>Ein finales Abschicken oder Einreichen des Projektes ist nicht erforderlich.</li>
                            </ul>
                            <h5>KONTROLLE & ÄNDERUNG NACH DER EINREICHFRIST</h5>
                            <ul>
                                <li>Nach dem 15.11. erfolgt die Kontrolle durch das AWA-Team und die Freigabe an die Jury.</li>
                                <li>Du wirst innerhalb von einer Woche nach der Einreichfrist per E-Mail (Spam-Ordner checken!)
                                    verständigt, für den Fall, dass du Änderungen/Korrekturen vornehmen musst.
                                    Für die Nachbearbeitung wird ein zeitliches Limit gesetzt, dass einzuhalten ist.
                                </li>
                                <li>Sobald dein Projekt freigegeben wurde, ändert sich der Status von „abgespeichert“ zu
                                    „freigegeben“.
                                </li>
                                <li>Die Rechnung wird im Jänner via E-Mail zugeschickt!</li>
                            </ul>
                            <h5>TECHNISCHER SUPPORT</h5>
                            <ul>
                                <li>Hast du Probleme beim Upload deines Projektes – lies bitte die <a
                                            target="_blank"
                                            href="https://austrianweddingaward.at/einreichung-beim-austrian-wedding-award/#faq">FAQs</a>,
                                    hier werden mögliche Fehlerquellen angegeben.
                                </li>
                                <li>Bei weiteren Fragen melde dich bitte bei uns: office@austrianweddingaward.at</li>
                                <li>Für eine schnelle Bearbeitung sind dein Usernamen, Passwort und die Projekt ID erforderlich.
                                </li>
                            </ul>
                            <h5>Dein AWA - Team</h5>
                            @if (count($projects) > 0)
                                <h2>Folgende Projekte weisen Fehler auf:</h2>
                                <p>Bitte auf das Projekt klicken um den Fehler zu beheben:</p>

                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Project ID</th>
                                        <th>Project Name</th>
                                        <th>Mangel</th>
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
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

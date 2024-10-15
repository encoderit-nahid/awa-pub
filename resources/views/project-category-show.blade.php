@extends('layouts.app')

@section('content')
    @if (session()->has('alert-success'))
        <div class="alert alert-success">
            {{ session()->get('alert-success') }}
        </div>
    @endif
    <input type = "hidden" name = "ajax_token" value = "{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <script>
                            document.write('<a href="' + document.referrer + '">Go Back</a>');
                        </script> - Projekte anzeigen für die Kategorie: {{ $cats->name }}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Vorname</th>
                                        <th>Firma</th>
                                        <th>Email</th>
                                        <th>Projektname</th>
                                        <th>Kategorie</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ $users[$project->user_id]['name'] }}</td>
                                            <td>{{ $users[$project->user_id]['vorname'] }}</td>
                                            <td>{{ $users[$project->user_id]['firma'] }}</td>
                                            <td>{{ $users[$project->user_id]['email'] }}</td>
                                            <td>{{ $project->projektname }}</td>
                                            <td>{{ $project->cat_name }}</td>
                                            <td>
                                                @if ($project->stat == 0)
                                                    @if ($project->stat == 0 && $project->is_selected_for_first_evaluation)
                                                        Zur Bewertung freigegeben
                                                    @else
                                                        abgespeichert
                                                    @endif
                                                @elseif($project->stat == 2)
                                                    @if ($project->jury == 0 && $project->inv == 0)
                                                        Für Rechnung freigegeben
                                                    @elseif ($project->jury == 0 && $project->inv == 1)
                                                        Zur Rechnungslegung freigeben
                                                    @elseif ($project->jury == 1)
                                                        Zur Bewertung freigegeben
                                                    @endif
                                                @elseif($project->stat == 3)
                                                    Zurückgewiesen
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>



                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

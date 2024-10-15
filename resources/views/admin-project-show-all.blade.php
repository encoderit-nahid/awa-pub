@extends('layouts.app')
@section('additional-styles')
    <style>
        .dataTables_filter {
            display: none;
        }
    </style>
@endsection
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
                    <div class="card-header">Projekte anzeigen...</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 mx-auto text-right">
                                <form action="{{ url('admin-project-show-all') }}">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                                            aria-describedby="basic-addon2" name="search">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-outline-secondary"
                                                type="button">Search</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="project-data" class="table table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th onclick="getSort(0)">Name</th>
                                        <th onclick="getSort(1)">Vorname</th>
                                        <th onclick="getSort(2)">Firma</th>
                                        <th onclick="getSort(3)">Email</th>
                                        <th onclick="getSort(4)">Projektname</th>
                                        <th onclick="getSort(5)">Kategorie</th>
                                        <th onclick="getSort(6)">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ $users[$project->user_id]['name'] }}</td>
                                            <td>{{ $users[$project->user_id]['vorname'] }}</td>
                                            <td>{{ $users[$project->user_id]['firma'] }}</td>
                                            <td>{{ $users[$project->user_id]['email'] }}</td>
                                            <td>
                                                <a
                                                    href="{{ url('/project/' . $project->id) }}">{{ $project->projektname }}</a>
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ url('/project/' . $project->id) }}">{{ $project->cat_name }}</a>
                                            </td>
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


                        @if ($is_paginate)
                            {{ $projects->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('additional-js')
    <script>
        var table = '';
        let pageLength = localStorage.getItem('pageLength');
        if (pageLength === null) {
            pageLength = 10;
        }

        $(document).ready(function() {
            table = $('#project-data').DataTable({
                "paging": true,
                "info": false,
                "pageLength": pageLength,
                columnDefs: [{
                    targets: [0],
                    orderData: [0, 1]
                }, {
                    targets: [1],
                    orderData: [1, 0]
                }, {
                    targets: [6],
                    orderData: [6, 0]
                }]
            });
            let activeSort = localStorage.getItem('activeSort');
            let activeOrder = localStorage.getItem('activeOrder');
            if (activeSort === null) activeSort = 0;
            if (activeOrder === null) activeOrder = 'asc';

            table.order([activeSort, activeOrder]).draw();

            $(document).on('change', "select[name='project-data_length']", function() {
                var base_url = "{{ url()->full() }}";

                var data = $(this).val();
                localStorage.setItem('pageLength', parseInt(data));

                var queries = getUrlVars()
                queries.per_page = data

                window.location.href = location.origin + location.pathname + qsStringify(queries);
            });
        });

        function qsStringify(queries) {
            return `?` + Object.entries(queries)
                .map(function([key, value]) {
                    return `${key}=${value}`
                }).join('&')
        }

        function getUrlVars() {
            var vars = [],
                hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        function getSort(sortNumber) {
            if (table.order()[0][1] == 'asc') {
                localStorage.setItem('activeOrder', 'desc');
            } else {
                localStorage.setItem('activeOrder', 'asc');
            }
            localStorage.setItem('activeSort', sortNumber);
        }
    </script>
@endsection

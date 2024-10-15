@extends('layouts.vote-app')

@section('content')
    @if (session()->has('alert-success'))
        <div class="alert alert-success">
            {{ session()->get('alert-success') }}
        </div>
    @endif


    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

            </div>

        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                            Public Vote Scores
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No. of User</th>
                                    <th>Project Name</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $user_id = 0;
                                    $sum = 0;
                                    $total_sum = 0;
                                    $flag = 1;
                                @endphp
                                @foreach ($counts as $count)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            {{ $count->total_user }}
                                        </td>
                                        <td>
                                            {{ $count->projektname }}
                                        </td>
                                        <td>{{ $count->score }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.5.js"></script>
@endsection

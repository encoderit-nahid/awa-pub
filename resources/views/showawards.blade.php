@extends('layouts.app')

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
                    <div class="card-header">Awards</div>

                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project ID</th>
                                    <th>Firmenname</th>
                                    <th>Badge Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($awards as $award)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            <?php $userId = null;
                                            foreach ($projects as $key => $value) {
                                                if ($award->project_id == $value->id) {
                                                    // echo $value->name;
                                                    echo $value->projektname;
                                                    $userId = $value->user_id;
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php foreach ($users as $key => $value) {
                                                if ($userId == $value->id) {
                                                    echo $value->firma;
                                                    break;
                                                }
                                            } ?>
                                        </td>
                                        <td>
                                            <?php
                                            foreach ($badges as $badge) {
                                                if ($badge->id == $award->badge_id) {
                                                    echo $badge->title;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td> <a href="{{ url('show-data-award/' . $award->id) }}">
                                                <button class="btn btn-primary">Edit</button></a> </td>
                                        <td> <a href="{{ url('delete-award/' . $award->id) }}">
                                                <button class="btn btn-danger">Delete</button></a> </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
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

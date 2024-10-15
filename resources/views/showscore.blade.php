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
                    <div class="card-header d-flex justify-content-between">
                        Scores

                        <a href="{{ url('export-score') }}">
                            <button class="btn btn-primary">Export</button>
                        </a>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
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
                                    <?php
                                    if ($i == 1) {
                                        $user_id = $count->user_id;
                                        $sum += (int) $count->score;
                                    } else {
                                        if ($user_id != $count->user_id) {
                                            $user_id = $count->user_id;
                                            $total_sum = $sum;
                                            $sum = 0;
                                            $flag = 0;
                                        } else {
                                            $flag = 1;
                                            $sum += (int) $count->score;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <?php if($flag){ ?>
                                        <td>{{ $i }}</td>
                                        <td>
                                            <?php foreach ($users as $user) {
                                                if ($user->id == $user_id) {
                                                    echo $user->name;
                                                    break;
                                                }
                                            } ?>
                                        </td>
                                        <td>
                                            {{ $count->name }}
                                        </td>
                                        <td>{{ $count->score }}</td>
                                        <?php }else{ ?>
                                        <td></td>
                                        <td></td>
                                        <td>Total score:</td>
                                        <td><?php echo $total_sum; ?></td>
                                        <?php } ?>
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

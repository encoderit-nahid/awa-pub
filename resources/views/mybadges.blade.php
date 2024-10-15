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
                    <div class="card-header">My Badges</div>

                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>category</th>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($badges as $badge)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            <?php foreach ($cats as $key => $value) {
                                                if ($badge->cat_id == $value->id) {
                                                    echo $value->name;
                                                }
                                            } ?>
                                        </td>
                                        <td>{{ $badge->title }}</td>
                                        <td>{{ $badge->year }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $badge->image) }}" alt="{{ $badge->title }}"
                                                style="width: 100px;cursor: pointer;"
                                                onclick='downloadImage("{{ asset('storage/' . $badge->image) }}", "{{ $badge->title }}")'>
                                        </td>
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
    <script type="text/javascript">
        function replaceAll(string, search, replace) {
            return string.split(search).join(replace);
        }

        function downloadImage(url, name) {
            fetch(url)
                .then(resp => resp.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    // the filename you want
                    a.download = replaceAll(name, '.', '');
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(() => alert('An error sorry'));
        }
    </script>
@endsection

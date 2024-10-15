@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-wrap">
            @php
                $galleries = json_decode($galleries[0]->image_path);
            @endphp
            @foreach ($galleries as $item)
                <div style="width:25%" class="mx-2">
                    <img src="{{ url('storage/' . $item) }}" alt="" class="w-100">
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.5.js"></script>
@endsection

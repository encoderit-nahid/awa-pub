@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Edit YoutubeUpload #{{ $youtubeupload->id }}</div>
                    <div class="card-body">
                        <a href="{{ url('/youtube-uploads') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($youtubeupload, [
                            'method' => 'PATCH',
                            'url' => ['/youtube-uploads', $youtubeupload->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('youtube-uploads.form', ['formMode' => 'edit'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

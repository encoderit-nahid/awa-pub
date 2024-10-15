@extends('layouts.app')
<style type="text/css">
    .form-check-label {
        padding-left: 30px;
    }
</style>
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
                    <div class="card-header">{{ __('Sichtbarkeit der öffentlichen Abstimmung') }}</div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('project-votable') }}">
                            @csrf
                            <div class="container ">
                                <div class="form-check ">
                                    <input class="form-check-input" type="radio" name="vote" id="flexRadioDefault1"
                                        value="0" @if ($vote->is_votable == 0) checked @endif>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Öffentliche Abstimmung deaktivieren
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="vote" id="flexRadioDefault2"
                                        value="1" @if ($vote->is_votable == 1) checked @endif>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Öffentliche Abstimmung aktivieren
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('aktualisieren') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"></script>
@endsection

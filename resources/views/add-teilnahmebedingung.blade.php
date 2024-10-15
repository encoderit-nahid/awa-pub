@extends('layouts.app')

@section('content')
@if(session()->has('alert-success'))
    <div class="alert alert-success">
        {{ session()->get('alert-success') }}
    </div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Teilnahmebedingungen akzeptieren') }}</div>

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

                    <form method="POST" action="{{ route('add-teilnahmebedingung') }}">
                        @csrf
                        Liebe/r {{ $user->vorname}}, bitte bestätige Teilnahmebedingungen für den Austrian Wedding Award: <br><br>
                        <input name="teilnahmebedingung" type="checkbox" id="teilnahmebedingung" value="1">   Ich habe die <a href=" https://austrianweddingaward.at/einreichung-beim-austrian-wedding-award/#teilnahmebedingung">Teilnahmebedingungen</a> gelesen.<br>
                        <br>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Akzeptieren') }}
                        </button>
                        <br>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

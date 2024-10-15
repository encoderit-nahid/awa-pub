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
                <div class="card-header">Schritt 2: Daten sammeln für die Kategorie: {{ $cats->name }}</div>
                  <div class="card-body">
                    <form method="POST" action="{{ route('next-view') }}">
                        @csrf

                        {{ Form::hidden('cat_id', $cats->id) }}
                      {{--
                        Für die Kategorie: {{ $cats->name }} benötigen Sie: <br><br>
                        {{ $cats->hints }} sowie <br><br>

                        {{ $cats->beschreibung }} <br>
                        {{ $cats->ort }} <br>
                        {{ $cats->referenz }} <br>
                        {{ $cats->extra }} --}}  <br>
                        {!! $cats->fulldescription !!}


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                              <a href="{{ url()->previous() }}" class="btn btn-default">Back</a>
                              <button type="submit" class="btn btn-primary">
                                  {{ __('Nächster Schritt: Daten eintragen') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection

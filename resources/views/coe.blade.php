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
                <div class="card-header">Schritt 1: Namen auswählen - User wird zum Mitglied des CoE</div>

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

                  <form method="POST" action="{{ route('addToCoE') }}">
                      @csrf

                        <label for="Users"></label>
                            <select class="form-control" name="id" id="id" data-parsley-required="true" onchange='this.form.submit()'>
                                @foreach ($users as $sn)
                                  {
                                      <option value="{{ $sn->id }}"> {{ $sn->firma }} - {{ $sn->name }} {{ $sn->vorname }} </option>
                                      }
                                @endforeach
                            </select>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>


 <script src="{{ asset('js/custom.js') }}"></script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

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

                        <form method="POST" action="{{ url('/register-new-user') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="anr"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Anrede') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="anr" id="anr">
                                        <option value="Frau">Frau</option>
                                        <option value="Herr">Herr</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Nachname') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                        value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="vorname"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Vorname') }}</label>

                                <div class="col-md-6">
                                    <input id="vorname" type="text"
                                        class="form-control{{ $errors->has('vorname') ? ' is-invalid' : '' }}"
                                        name="vorname" value="{{ old('vorname') }}" required>

                                    @if ($errors->has('vorname'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('vorname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="firma"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Firma') }}</label>

                                <div class="col-md-6">
                                    <input id="firma" type="text"
                                        class="form-control{{ $errors->has('firma') ? ' is-invalid' : '' }}" name="firma"
                                        value="{{ old('firma') }}" required>

                                    @if ($errors->has('firma'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('firma') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                        value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                            <label for="voucher" class="col-md-4 col-form-label text-md-right">{{ __('Voucher') }}</label>

                            <div class="col-md-6">
                                <input id="voucher" type="voucher" class="form-control{{ $errors->has('voucher') ? ' is-invalid' : '' }}" name="voucher" value="{{ old('voucher') }}">

                                @if ($errors->has('voucher'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('voucher') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <input name="teilnahmebedingung" type="checkbox" id="teilnahmebedingung" value="1">
                                    Ich habe die <a
                                        href="https://austrianweddingaward.at/einreichung-beim-austrian-wedding-award/#teilnahmebedingung">Teilnahmebedingungen</a>
                                    gelesen.
                                </div>
                            </div>

                            {{-- <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    Ich hab die Regeln zum Award gelesen und bin mit den <a
                                        href="https://austrianweddingaward.at/einreichung-beim-austrian-wedding-award/#teilnahmebedingung">Richtlinien</a>
                                    einverstanden
                                    <input name="agb" type="checkbox" id="agb" value="1">
                                </div>
                            </div> --}}

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <input name="datenschutz" type="checkbox" id="datenschutz" value="1">
                                    Ich bin damit einverstanden, dass meine hier <a
                                        href="https://www.austrianweddingaward.at/datenschutzerklaerung">angegebenen
                                        Daten</a> zur Bearbeitung der
                                    Einreichung verwendet werden.
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <input name="newsletter" type="checkbox" id="newsletter" value="1">
                                    Ich möchte über AWA-Neuigkeiten informiert werden
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

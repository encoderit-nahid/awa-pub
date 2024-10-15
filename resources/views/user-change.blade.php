@extends('layouts.app')

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
                    <div class="card-header">{{ __('Daten ändern') }}</div>

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

                        <form method="POST" action="{{ route('user-change') }}">
                            @csrf

                            @if ($user->first === 0)
                                <div class="form-group row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-8">
                                        <h2>Ändern/Korrigieren/Hinzufügen</h2>
                                        <p>Bitte ändere/korrigiere Deine Firmendaten!</p>
                                        <p>Wenn Du das erste Mal beim Austrian Wedding Award teilnimmst, </p>
                                        <p>dann fülle bitte alle Felder aus!</p>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                <label for="anr"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Anrede*') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="anr" id="anr">
                                        <option value="Herr" {{ $user->anr === 'Herr' ? 'selected' : '' }}>Herr</option>
                                        <option value="Frau" {{ $user->anr === 'Frau' ? 'selected' : '' }}>Frau</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="titel"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Titel') }}</label>

                                <div class="col-md-6">
                                    <input id="titel" type="text" value="{{ $user->titel }}"
                                        class="form-control{{ $errors->has('titel') ? ' is-invalid' : '' }}" name="titel"
                                        value="{{ old('titel') }}">

                                    @if ($errors->has('titel'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('titel') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Nachname*') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" value="{{ $user->name }}"
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
                                    class="col-md-4 col-form-label text-md-right">{{ __('Vorname*') }}</label>

                                <div class="col-md-6">
                                    <input id="vorname" type="text" value="{{ $user->vorname }}"
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
                                <label for="form"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Unternehmensform*') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="form" id="form">
                                        <option value="Einzelfirma"
                                            {{ $user->form === 'Einzelunternehmen' ? 'selected' : '' }}>Einzelunternehmen
                                        </option>
                                        <option value="GmbH" {{ $user->form === 'GmbH' ? 'selected' : '' }}>GmbH</option>
                                        <option value="KG" {{ $user->form === 'KG' ? 'selected' : '' }}>KG</option>
                                        <option value="OG" {{ $user->form === 'OG' ? 'selected' : '' }}>OG</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="firma"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Firma*') }}</label>

                                <div class="col-md-6">
                                    <input id="firma" type="text" value="{{ $user->firma }}"
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
                                <label for="ausgesprochen"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Wie wird der Firmenname ausgesprochen') }}</label>

                                <div class="col-md-6">
                                    <input id="ausgesprochen" type="text" value="{{ $user->ausgesprochen }}"
                                        class="form-control{{ $errors->has('firma') ? ' is-invalid' : '' }}"
                                        name="ausgesprochen" value="{{ old('ausgesprochen') }}">

                                    @if ($errors->has('ausgesprochen'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('ausgesprochen') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="werden"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Welcher Name soll auf der Urkunde vermerkt werden') }}</label>

                                <div class="col-md-6">
                                    <input id="werden" type="text" value="{{ $user->werden }}"
                                        class="form-control{{ $errors->has('firma') ? ' is-invalid' : '' }}" name="werden"
                                        value="{{ old('werden') }}">

                                    @if ($errors->has('werden'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('werden') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fb"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Postadresse') }}</label>
                            </div>
                            <div class="form-group row">
                                <label for="adresse"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Adresse*') }}</label>

                                <div class="col-md-6">
                                    <input id="adresse" type="text" value="{{ $user->adresse }}"
                                        class="form-control{{ $errors->has('adresse') ? ' is-invalid' : '' }}"
                                        name="adresse" value="{{ old('adresse') }}" required>

                                    @if ($errors->has('adresse'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('adresse') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="plz"
                                    class="col-md-4 col-form-label text-md-right">{{ __('PLZ*') }}</label>

                                <div class="col-md-6">
                                    <input id="plz" type="text" value="{{ $user->plz }}"
                                        class="form-control{{ $errors->has('plz') ? ' is-invalid' : '' }}" name="plz"
                                        value="{{ old('plz') }}" required>

                                    @if ($errors->has('plz'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('plz') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ort"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Ort*') }}</label>

                                <div class="col-md-6">
                                    <input id="ort" type="text" value="{{ $user->ort }}"
                                        class="form-control{{ $errors->has('ort') ? ' is-invalid' : '' }}" name="ort"
                                        value="{{ old('ort') }}" required>

                                    @if ($errors->has('ort'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('ort') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="bundesland"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Bundesland') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="bundesland" id="bundesland">
                                        <option
                                            {{ old('bundesland', $user->bundesland) == 'Burgenland' ? 'selected' : '' }}
                                            value="Burgenland">Burgenland</option>
                                        <option {{ old('bundesland', $user->bundesland) == 'Kärnten' ? 'selected' : '' }}
                                            value="Kärnten">Kärnten</option>
                                        <option
                                            {{ old('bundesland', $user->bundesland) == 'Niederösterreich' ? 'selected' : '' }}
                                            value="Niederösterreich">Niederösterreich</option>
                                        <option
                                            {{ old('bundesland', $user->bundesland) == 'Oberösterreich' ? 'selected' : '' }}
                                            value="Oberösterreich">Oberösterreich</option>
                                        <option {{ old('bundesland', $user->bundesland) == 'Salzburg' ? 'selected' : '' }}
                                            value="Salzburg">Salzburg</option>
                                        <option
                                            {{ old('bundesland', $user->bundesland) == 'Steiermark' ? 'selected' : '' }}
                                            value="Steiermark">Steiermark</option>
                                        <option {{ old('bundesland', $user->bundesland) == 'Tirol' ? 'selected' : '' }}
                                            value="Tirol">Tirol</option>
                                        <option {{ old('bundesland', $user->bundesland) == 'Wien' ? 'selected' : '' }}
                                            value="Wien">Wien</option>
                                        <option
                                            {{ old('bundesland', $user->bundesland) == 'Vorarlberg' ? 'selected' : '' }}
                                            value="Vorarlberg">Vorarlberg</option>
                                    </select>
                                </div>
                            </div>
                            <!-- by shaf start-->

                            <div class="form-group row">
                                <label for="rechnungsadresse"
                                    class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>
                                <div class="col-md-6">
                                    <input type="checkbox" @if ($user->rechnungsadresse == 1) checked="checked" @endif
                                        name="rechnungsadresse" id="rechnungsadresse"
                                        value="{{ $user->rechnungsadresse }}"><label for="rechnungsadresse"><span></span>
                                        <p> Rechnungsadresse = Postadresse </p>
                                    </label>
                                    @if ($errors->has('rechnungsadresse'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('rechnungsadresse') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row optional-field">
                                <label for="firma_re"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Firma*') }}</label>

                                <div class="col-md-6">
                                    <input id="firma_re" type="text" value="{{ $user->firma_re }}"
                                        class="form-control{{ $errors->has('firma_re') ? ' is-invalid' : '' }}"
                                        name="firma_re" value="{{ old('firma_re') }}" required>

                                    @if ($errors->has('firma_re'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('firma_re') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row optional-field">
                                <label for="adresse_re"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Adresse*') }}</label>

                                <div class="col-md-6">
                                    <input id="adresse_re" type="text" value="{{ $user->adresse_re }}"
                                        class="form-control{{ $errors->has('adresse_re') ? ' is-invalid' : '' }}"
                                        name="adresse_re" value="{{ old('adresse_re') }}" required>

                                    @if ($errors->has('adresse_re'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('adresse_re') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row optional-field">
                                <label for="plz_re"
                                    class="col-md-4 col-form-label text-md-right">{{ __('PLZ*') }}</label>

                                <div class="col-md-6">
                                    <input id="plz_re" type="text" value="{{ $user->plz_re }}"
                                        class="form-control{{ $errors->has('plz_re') ? ' is-invalid' : '' }}"
                                        name="plz_re" value="{{ old('plz_re') }}" required>

                                    @if ($errors->has('plz_re'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('plz_re') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row optional-field">
                                <label for="ort_re"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Ort*') }}</label>

                                <div class="col-md-6">
                                    <input id="ort_re" type="text" value="{{ $user->ort_re }}"
                                        class="form-control{{ $errors->has('ort_re') ? ' is-invalid' : '' }}"
                                        name="ort_re" value="{{ old('ort_re') }}" required>

                                    @if ($errors->has('ort_re'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('ort_re') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- by shaf ends-->
                            <div class="form-group row">
                                <label for="fb"
                                    class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>
                            </div>
                            <div class="form-group row">
                                <label for="founded"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Gründungsjahr*') }}</label>

                                <div class="col-md-6">
                                    <input id="founded" type="text" value="{{ $user->founded }}"
                                        class="form-control{{ $errors->has('founded') ? ' is-invalid' : '' }}"
                                        name="founded" value="{{ old('founded') }}" required>

                                    @if ($errors->has('founded'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('founded') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tel"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Telefonnummer*') }}</label>

                                <div class="col-md-6">
                                    <input id="tel" type="tel" value="{{ $user->tel }}"
                                        class="form-control{{ $errors->has('tel') ? ' is-invalid' : '' }}" name="tel"
                                        value="{{ old('tel') }}" required>

                                    @if ($errors->has('tel'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('tel') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fb"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Adresse') }}</label>
                            </div>
                            <div class="form-group row">
                                <label for="companymail"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Firmen E-Mail') }}</label>

                                <div class="col-md-6">
                                    <input id="companymail" type="text" value="{{ $user->companymail }}"
                                        class="form-control{{ $errors->has('companymail') ? ' is-invalid' : '' }}"
                                        name="companymail" value="{{ old('companymail') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Addresse*') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" value="{{ $user->email }}"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="url"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Website') }}</label>

                                <div class="col-md-6">
                                    <input id="url" type="text" value="{{ $user->url }}"
                                        class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                        name="url" value="{{ old('url') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fb"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Social Media') }}</label>
                            </div>
                            <div class="form-group row">
                                <label for="fb"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Facebook') }}</label>

                                <div class="col-md-6">
                                    <input id="fb" type="text" value="{{ $user->fb }}"
                                        class="form-control{{ $errors->has('fb') ? ' is-invalid' : '' }}" name="fb"
                                        value="{{ old('fb') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="instagram"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Instagram') }}</label>

                                <div class="col-md-6">
                                    <input id="instagram" type="text" value="{{ $user->instagram }}"
                                        class="form-control{{ $errors->has('instagram') ? ' is-invalid' : '' }}"
                                        name="instagram" value="{{ old('instagram') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fb"
                                    class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>
                            </div>
                            <div class="form-group row">
                                <label for="voucher"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Rabattcode') }}</label>

                                <div class="col-md-6">
                                    <input id="voucher" type="text" value="{{ $user->voucher }}"
                                        class="form-control{{ $errors->has('voucher') ? ' is-invalid' : '' }}"
                                        name="voucher" value="{{ old('voucher') }}">

                                    @if ($errors->has('voucher'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('voucher') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="atu"
                                    class="col-md-4 col-form-label text-md-right">{{ __('UID') }}</label>

                                <div class="col-md-6">
                                    <input id="atu" type="text" value="{{ $user->atu }}"
                                        class="form-control{{ $errors->has('atu') ? ' is-invalid' : '' }}"
                                        name="atu" value="{{ old('atu') }}">
                                </div>
                            </div>



                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Ändern') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {

            changeRequiredAttr();
            $("#rechnungsadresse").change(function() {
                changeRequiredAttr();
            });

            function changeRequiredAttr() {
                if ($("#rechnungsadresse").is(":checked")) {
                    $("#firma_re").removeAttr('required');
                    $("#adresse_re").removeAttr('required');
                    $("#plz_re").removeAttr('required');
                    $("#ort_re").removeAttr('required');
                    $(".optional-field").hide();
                    // console.log("Check box in Checked");
                } else {
                    $("#firma_re").attr("required", "true");
                    $("#adresse_re").attr("required", "true");
                    $("#plz_re").attr("required", "true");
                    $("#ort_re").attr("required", "true");
                    $(".optional-field").show();
                    // console.log("Check box is Unchecked");
                }
            }
        });
    </script>
@endsection

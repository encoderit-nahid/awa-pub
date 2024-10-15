<form method="POST" action="{{ route('add-project') }}">
    @csrf

    <div class="form-group row">
        <label for="cat_name" class="col-md-4 col-form-label text-md-right">{{ __('Kategorie') }}</label>

        <div class="col-md-6">
            <input id="cat_name" type="text" value="{{ $cats->name }}"
                class="form-control{{ $errors->has('cat_name') ? ' is-invalid' : '' }}" name="cat_name"
                value="{{ old('cat_name') }}" required readonly>

            @if ($errors->has('cat_name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('cat_name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    {{ Form::hidden('cat_id', $cats->id) }}
    {{ Form::hidden('group', $cats->group) }}



    @if ($user->rolle == 9)
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Select User') }}</label>

            <div class="col-md-6">
                <select name="user_id" id="input" class="form-control" required="required">
                    @foreach ($users as $single_user)
                        <option value="{{ $single_user->id }}">{{ $single_user->vorname }} {{ $single_user->name }} -
                            [{{ $single_user->firma }}]</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <input type="hidden" name="user_id" value="{{ $user->id }}">
    @endif

    <div class="form-group row">
        <label for="beschreibung" class="col-md-4 col-form-label text-md-right">{{ __('Beschreibung*') }}</label>

        <div class="col-md-6">

            <textarea id="beschreibung" rows="10" class="form-control{{ $errors->has('beschreibung') ? ' is-invalid' : '' }}"
                name="beschreibung" value="{{ old('beschreibung') }}" required></textarea>
            Erlaubte Wörter: <span class="beschreibung-word-limit">{{-- $cats->words --}} 150</span> - Wörter total :
            <span id="display_count">0</span> Wörter!
            @if ($errors->has('beschreibung'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('beschreibung') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="youtube"
               class="col-md-4 col-form-label text-md-right">{{ __('Video') }}</label>

        <div class="col-md-6">
            <input id="youtube" type="file"
                   class="form-control{{ $errors->has('youtube') ? ' is-invalid' : '' }}"
                   name="youtube" value="{{ old('youtube') }}" accept="video/mp4,video/x-m4v,video/*">
            <input type="hidden" id="uploaded-youtube-file-name" name="uploaded_youtube_file_name">
            @if ($errors->has('youtube'))
                <span class="invalid-feedback">
                                            <strong>{{ $errors->first('youtube') }}</strong>
                                        </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="datum" class="col-md-4 col-form-label text-md-right">{{ __('Datum des StyledShoots*') }}</label>

        <div class="col-md-6">
            <input id="datum" type="text" class="form-control{{ $errors->has('datum') ? ' is-invalid' : '' }}"
                name="datum" value="{{ old('datum') }}" required>

            @if ($errors->has('datum'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('datum') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="ort" class="col-md-4 col-form-label text-md-right">{{ __('Ort des StyledShoots*') }}</label>

        <div class="col-md-6">
            <input id="ort" type="text" class="form-control{{ $errors->has('ort') ? ' is-invalid' : '' }}"
                name="ort" value="{{ old('ort') }}" required>

            @if ($errors->has('ort'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('ort') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="extra" class="col-md-4 col-form-label text-md-right">{{ __('Teilnehmer*') }}</label>

        <div class="col-md-6">
            <textarea id="extras" rows="10" class="form-control{{ $errors->has('extra') ? ' is-invalid' : '' }}"
                name="extra" value="{{ old('extra') }}" required></textarea>
            Erlaubte Wörter: {{ $cats->words }} - Wörter total : <span id="display_count_extras">0</span> Wörter!
            @if ($errors->has('extra'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('extra') }}</strong>
                </span>
            @endif
        </div>
    </div>


    <div class="form-group row">
        <label for="copyright" class="col-md-4 col-form-label text-md-right">{{ __('Copyright*') }}</label>

        <div class="col-md-6">
            <input id="copyright" type="text"
                class="form-control{{ $errors->has('copyright') ? ' is-invalid' : '' }}" name="copyright"
                value="{{ old('copyright') }}" required>

            @if ($errors->has('copyright'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('copyright') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="check" class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>

        <div class="col-md-6">
            <input type="checkbox" checked="checked" name="check" id="check" value="{{ old('check') }}"
                required><label for="check"><span></span>
                <p>Ich habe das Recht die Fotos zu verwenden!</p>
            </label>
            @if ($errors->has('check'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('check') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <a href="{{ url()->previous() }}" class="btn btn-default">Back</a>
            <button type="submit" class="btn btn-primary">
                {{ __('Projekt anlegen') }}
            </button>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-1.5.js"></script>
    <script>
        $(document).ready(function() {
            var max = $('.beschreibung-word-limit').text();
            $("#beschreibung").on('keyup', function() {
                var words = this.value.match(/\S+/g).length;

                if (words > max) {
                    // Split the string on first 200 words and rejoin on spaces
                    var trimmed = $(this).val().split(/\s+/, max).join(" ");
                    // Add a space at the end to make sure more typing creates new words
                    $(this).val(trimmed + " ");
                } else {
                    $('#display_count_beschreibung').text(words);
                    $('#word_left').text(max - words);
                }
            });

            $("#testimonial").on('keyup', function() {
                var max = {{ $cats->words }};
                var words = this.value.match(/\S+/g).length;

                if (words > max) {
                    // Split the string on first 200 words and rejoin on spaces
                    var trimmed = $(this).val().split(/\s+/, max).join(" ");
                    // Add a space at the end to make sure more typing creates new words
                    $(this).val(trimmed + " ");
                } else {
                    $('#display_count_referenz').text(words);
                    $('#word_left').text(max - words);
                }
            });

            $("#extras").on('keyup', function() {
                var max = {{ $cats->words }};
                var words = this.value.match(/\S+/g).length;

                if (words > max) {
                    // Split the string on first 200 words and rejoin on spaces
                    var trimmed = $(this).val().split(/\s+/, max).join(" ");
                    // Add a space at the end to make sure more typing creates new words
                    $(this).val(trimmed + " ");
                } else {
                    $('#display_count_extras').text(words);
                    $('#word_left').text(max - words);
                }
            });
        });
    </script>
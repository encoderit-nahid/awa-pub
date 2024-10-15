<script src="{{ url('js/dropzone.js') }}"></script>
<link rel="stylesheet" href="{{ url('css/dropzone.css') }}">
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
                    <div class="card-header">Schritt 4: Bilder uploaden - Projektnummer: {{ $project_id }} - Max.
                        {{ $max_img }} Fotos
                    </div>
                    @if ($max_img > 0)
                        <div class="card-body">
                            <form action="{{ url('/images-save') }}" data-count="{{ $cats->count }}"
                                enctype="multipart/form-data" class="dropzone" id="my-dropzone">
                                {{ csrf_field() }} {{ Form::hidden('cat_id', $cats->id) }}
                                {{ form::hidden('project_id', $project_id, ['id' => 'project_id']) }}
                            </form>
                        </div>

                        <div class="row" id="ferting-btn" style="display: none;">
                            <div class="col-sm-4 col-sm-offset-4">
                                <a class="btn btn-success btn-block" href="{{ route('project-show') }}"><b>Project
                                        speichern!</b></a>
                            </div>
                        </div>
                    @else
                        <br>
                        <br>
                        <br>
                        <h2 class="text-center">Sie können keine Bilder mehr hinzufügen!</h2>
                        <br>
                        <br>
                        <br>
                        <div class="row" id="ferting-btn">
                            <div class="col-sm-4 col-sm-offset-4">
                                <a class="btn btn-success btn-block" href="{{ route('project-show') }}"><b>Back</b></a>
                            </div>
                        </div>
                    @endif


                    <br>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
    <input type="hidden" name="ajax_token" value="{{ csrf_token() }}">
    <script>
        var fileName = null;
        var total_photos_counter = 0;
        var max = {{ $cats->count }};
        Dropzone.options.myDropzone = {
            maxFiles: '{{ $max_img }}',
            paramName: 'file',
            maxFilesize: 2, // MB
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            init: function() {
                this.on("removedfile", function(file) {
                    var token = $('input[name="ajax_token"]').val();
                    $.ajax({
                        url: "{{ url('/project/delete-image-instant') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        data: {
                            id: $('#project_id').val(),
                            imgName: fileName
                        },
                        success: function(response) {
                            // location.reload();
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });

                });
            },

            success: function(file, done) {
                const res = JSON.parse(done);
                fileName = res.fileName;
                total_photos_counter++;
                $("#counter").text("# " + total_photos_counter);
                if (total_photos_counter > 0) {
                    $('#ferting-btn').show();
                } else {
                    $('#ferting-btn').hide();
                }
            }
        };
    </script>
@endsection

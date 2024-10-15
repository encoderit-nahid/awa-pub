@extends('layouts.app')
@section('additional-styles')
    <link rel="stylesheet" href="{{ asset('css/single-project.css') }}">
@endsection
@section('content')

    @if (session()->has('alert-success'))
        <div class="alert alert-success">
            {{ session()->get('alert-success') }}
        </div>
    @endif
    <input type="hidden" name="ajax_token" value="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $project->projektname }}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        @if ($project->stat == 0)
                            <button class="btn btn-primary action-accept" id="{{ $project->id }}">OK</button>
                            <button class="btn btn-primary action-inv" id="{{ $project->id }}">OK + Rechnung</button>
                            <button class="btn btn-primary action-jury" id="{{ $project->id }}">OK + Jury</button>
                            <button class="btn btn-primary action-reject" id="{{ $project->id }}">Mängel</button>
                        @elseif($project->stat == 1)
                            <button class="btn btn-primary action-accept" id="{{ $project->id }}">OK</button>
                            <button class="btn btn-primary action-inv" id="{{ $project->id }}">OK + Rechnung</button>
                            <button class="btn btn-primary action-jury" id="{{ $project->id }}">OK + Jury</button>
                        @elseif($project->stat == 3)
                            <button class="btn btn-primary action-accept" id="{{ $project->id }}">OK</button>
                            <button class="btn btn-primary action-inv" id="{{ $project->id }}">OK + Rechnung</button>
                            <button class="btn btn-primary action-jury" id="{{ $project->id }}">OK + Jury</button>
                        @elseif($project->stat == 2)
                            <button class="btn btn-primary action-reject" id="{{ $project->id }}">Mängel</button>
                            @if ($project->jury != 1)
                                <button class="btn btn-primary action-add-jury" id="{{ $project->id }}">Zur Jury</button>
                            @else
                                <button class="btn btn-primary action-remove-jury" id="{{ $project->id }}">Jury
                                    löschen</button>
                            @endif
                            @if ($project->inv != 1)
                                <button class="btn btn-primary action-add-inv" id="{{ $project->id }}">Zur
                                    Invoice</button>
                            @else
                                <button class="btn btn-primary action-remove-inv" id="{{ $project->id }}">Jury
                                    löschen</button>
                            @endif
                        @endif
                        @if ($project->free == 0)
                            <button class="btn btn-primary action-free" dataValue="1" id="{{ $project->id }}">Gratis
                                Projekt</button>
                        @else
                            <button class="btn btn-primary action-free" dataValue="0" id="{{ $project->id }}">Kein Gratis
                                Projekt</button>
                        @endif
                        @if ($project->service == 0)
                            <button class="btn btn-primary action-service" dataValue="1"
                                id="{{ $project->id }}">UploadService (Extra Kosten)</button>
                        @else
                            <button class="btn btn-primary action-service" dataValue="0" id="{{ $project->id }}">kein
                                UploadService</button>
                        @endif

                        @if ($project->special == 0)
{{--                            <button class="btn btn-primary action-special" dataValue="1" id="{{ $project->id }}">Spezialprojekt</button>--}}
                        @else
                            <button class="btn btn-primary action-special" dataValue="0" id="{{ $project->id }}">Kein Spezialprojekt mehr</button>
                        @endif

                        <a href="{{ url('/project/edit-image/' . $project->id . '/' . $project->cat_id) }}"
                            class="btn btn-primary">Bild(er) ändern</a>
                        @if ($project->stat != 1)
                            <button class="btn btn-primary action-delete" id="{{ $project->id }}">Löschen</button>
                        @endif
                        <form method="POST" action="{{ route('project-change') }}">
                            @csrf
                            {{ Form::hidden('projectID', $project->id) }}
                            {{ Form::hidden('catID', $project->cat_id) }}
                            <button type="submit" class="btn btn-primary" value="change" name="submit">
                                {{ __('Ändern') }}
                            </button>
                        </form>


                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h1>Project Detail:</h1>
                                <br>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <p><b>Name: </b> {{ $project->projektname }}</p>
                                <p><b>Projekt name: </b> {{ $project->projektname }}</p>
                                <p><b>Category: </b> {{ $project->cat_name }}</p>
                                <p><b>group: </b> {{ $project->group }}</p>
                                <p><b>Beschreibung: </b> {{ $project->beschreibung }}</p>
                                <p><b>Youtube: </b> {{ $project->youtube }}</p>
                                <p><b>Copyright: </b> {{ $project->copyright }}</p>
                                <p><b>Service: </b> {{ $project->service }}</p>
                                {{-- <p><b>Star Rating: </b>

                                            @php
                                                $point = 100 - (($max - $project->count->sum('counts'))/$max) *100;
                                                if($point>=60){
                                                    $star = 4;
                                                }elseif($point>=20){
                                                    $star = 3;
                                                }else{
                                                    $star = 2;
                                                }
                                            @endphp
                                            @if ($star == 4)
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                            @elseif($star == 3)
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                            @elseif($star == 2)
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                                      <i class="fas fa-star en-rating-yellow"></i>
                                            @endif



                      - Punkte insgesamt: {{ $project->count->sum('counts') }} </p> --}}
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <p><b>Testimonial: </b> {{ $project->testimonial }}</p>
                                <p><b>Check: </b> {{ $project->check }}</p>
                                <p><b>Ort: </b> {{ $project->ort }}</p>
                                <p><b>Datum: </b> {{ $project->datum }}</p>
                                <p><b>Stat: </b> {{ $project->stat }}</p>
                                <p><b>Created_at: </b> {{ $project->created_at }}</p>
                                <p><b>Extra: </b> {{ $project->extra }}</p>
                                <p><b>Invoice: </b> {{ $project->inv }}</p>
                                <p><b>Jury: </b> {{ $project->jury }}</p>
                                <p><b>Free: </b> {{ $project->free }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h1>User Detail:</h1>
                                <br>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <p><b>Anr: </b> {{ $current_user->anr }}</p>
                                <p><b>Titel: </b> {{ $current_user->titel }}</p>
                                <p><b>Vorname: </b> {{ $current_user->vorname }}</p>
                                <p><b>Name: </b> {{ $current_user->name }}</p>
                                <p><b>Email: </b> {{ $current_user->email }}</p>
                                <p><b>Status: </b> {{ $current_user->status }}</p>
                                <p><b>Rolle: </b> {{ $current_user->rolle }}</p>
                                <p><b>Firma: </b> {{ $current_user->firma }}</p>
                                <p><b>Form: </b> {{ $current_user->form }}</p>
                                <p><b>Adresse: </b> {{ $current_user->adresse }}</p>
                                <p><b>Plz: </b> {{ $current_user->plz }}</p>
                                <p><b>Ort: </b> {{ $current_user->ort }}</p>
                                <p><b>Bundesland: </b> {{ $current_user->bundesland }}</p>
                                <p><b>Wie wird der Firmenname ausgesprochen: </b> {{ $current_user->ausgesprochen ?? 'N/A' }}</p>
                                <p><b>Welcher Name soll auf der Urkunde vermerkt werden: </b> {{ $current_user->werden ?? 'N/A' }}</p>

                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <p><b>Tel: </b> {{ $current_user->tel }}</p>
                                <p><b>Founded: </b> {{ $current_user->founded }}</p>
                                <p><b>Url: </b> {{ $current_user->url }}</p>
                                <p><b>Companymail: </b> {{ $current_user->companymail }}</p>
                                <p><b>UID: </b> {{ $current_user->atu }}</p>
                                <p><b>FB: </b> {{ $current_user->fb }}</p>
                                <p><b>First: </b> {{ $current_user->first }}</p>
                                <p><b>Agb: </b> {{ $current_user->agb }}</p>
                                <p><b>Newsletter: </b> {{ $current_user->newsletter }}</p>
                                <p><b>Datenschutz: </b> {{ $current_user->datenschutz }}</p>
                                <p><b>Project: </b> {{ $current_user->project }}</p>
                                <p><b>Facebook: </b> {{ $current_user->fb }}</p>
                                <p><b>Instagram: </b> {{ $current_user->instagram }}</p>
                                <p><b>Rabattcode: </b> {{ $current_user->voucher ?? 'N/A' }}</p>
                                <p><b>Created_at: </b> {{ $current_user->created_at }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <?php $imageCount = 0; ?>
                            @foreach ($project->images as $image)
                                @php
                                    $imageCount++;
                                    $thumb_url = $image->thumb_url;
                                    if (substr($thumb_url, 0, 1) != '/') {
                                        $thumb_url = '/' . $thumb_url;
                                    }
                                    $filename = $image->filename;
                                    if (substr($filename, 0, 1) != '/') {
                                        $filename = '/' . $filename;
                                    }
                                    $url = $image->url;
                                    if (substr($url, 0, 1) != '/') {
                                        $url = '/' . $url;
                                    }
                                    
                                @endphp

                                <div class="column" id="thumb-<?php echo md5($filename); ?>">
                                    <img src="{{ url($thumb_url) }}" alt="{{ $filename }}"
                                        style="width:70%;height:70%"
                                        onclick="openModal('{{ $project->projektname }}');currentSlide(<?php echo $imageCount; ?> , '<?php echo $project->projektname; ?>')"
                                        class="hover-shadow cursor">
                                </div>
                            @endforeach

                        </div>
                        <br>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div id="myModal-{{ $project->projektname }}" class="modal modal-height-width en-p-20">
                                    <span class="close cursor en-fs-30"
                                        onclick="closeModal('{{ $project->projektname }}')">&times;</span>
                                    <div class="modal-content p-relative en-p-40 w-auto-h-100">
                                        <div class="wide_wrapper text-center big-slider-image-container w-auto-h-100">
                                            @foreach ($project->images as $image)
                                                @php
                                                    $thumb_url = $image->thumb_url;
                                                    if (substr($thumb_url, 0, 1) != '/') {
                                                        $thumb_url = '/' . $thumb_url;
                                                    }
                                                    $filename = $image->filename;
                                                    if (substr($filename, 0, 1) != '/') {
                                                        $filename = '/' . $filename;
                                                    }
                                                    $url = $image->url;
                                                    if (substr($url, 0, 1) != '/') {
                                                        $url = '/' . $url;
                                                    }
                                                    
                                                @endphp

                                                <div class="mySlides-<?php echo $project->projektname; ?> w-auto-h-100"
                                                    data-responsive="true" id="wide-<?php echo md5($filename); ?>">

                                                    <img src="{{ url($url) }}"
                                                        class="big-slider-image img-responsive w-auto-h-100 en-m-auto"
                                                        alt="Nature and sunrise">

                                                </div>
                                            @endforeach

                                        </div>
                                        <a class="prev en-fs-20"
                                            onclick="plusSlides(-1 , '<?php echo $project->projektname; ?>')">&#10094;</a>
                                        <a class="next en-fs-20"
                                            onclick="plusSlides(1 , '<?php echo $project->projektname; ?>')">&#10095;</a>

                                        <div style="height : 30px;">
                                        </div>

                                        <div class="clearfix">
                                            <?php $imageCount = 0; ?>
                                            @foreach ($project->images as $image)
                                                @php
                                                    $imageCount++;
                                                    $thumb_url = $image->thumb_url;
                                                    if (substr($thumb_url, 0, 1) != '/') {
                                                        $thumb_url = '/' . $thumb_url;
                                                    }
                                                    $filename = $image->filename;
                                                    if (substr($filename, 0, 1) != '/') {
                                                        $filename = '/' . $filename;
                                                    }
                                                    $url = $image->url;
                                                    if (substr($url, 0, 1) != '/') {
                                                        $url = '/' . $url;
                                                    }
                                                    
                                                @endphp
                                                @if ($user->rolle === 0)
                                                    <div class="column clearfix" id="slide-<?php echo md5($filename); ?>">
                                                        <div class="clearfix text-center" style="background : grey">

                                                        </div>
                                                        <div class="image-wrapper">
                                                            <img id="slideimg-<?php echo md5($filename); ?>"
                                                                class="demo-<?php echo $project->projektname; ?> cursor"
                                                                src="{{ url($thumb_url) }}" style="width:100%"
                                                                onclick="currentSlide(<?php echo $imageCount; ?> , '<?php echo $project->projektname; ?>')"
                                                                alt="Nature and sunrise">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>

                                    </div>
                                    <div style="height : 80px"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="Modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Wollen Sie dieses Projekt wirklich löschen?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-deep-orange" id="model-final-delete">Ja</button>
                    <button class="btn btn-deep-orange" id="model-delete-cancel">Nein</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Grund des Mangels</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="md-form">
                        <i class="fa fa-pencil prefix grey-text"></i>
                        <textarea type="text" id="form8" class="md-textarea form-control email-body" rows="4"></textarea>
                        {{-- <label data-error="wrong" data-success="right" for="form8">Deine Nachricht</label> --}}
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-deep-orange" id="model-send-email">E-Mail senden und Projekt
                        zurückweisen</button>
                    <button class="btn btn-deep-orange" id="model-cancel">Abbruch</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {


            var token = $('input[name="ajax_token"]').val();


            $(document).on('click', '.action-add-jury', function() {
                $.ajax({
                    url: "{{ url('/project-jury-add-admin') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id')
                    },
                    success: function(response) {
                        // alert("Project has been successfully accepted.");
                        location.reload();
                    }
                });
            });


            $(document).on('click', '.action-add-inv', function() {
                $.ajax({
                    // url: "{{ url('/project-inv-add-admin') }}".replace("http://", "https://"),
                    url: "{{ url('/project-inv-add-admin') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id')
                    },
                    success: function(response) {
                        // alert("Project has been successfully accepted.");
                        location.reload();
                    }
                });
            });



            $(document).on('click', '.action-remove-jury', function() {
                $.ajax({
                    url: "{{ url('/project-jury-remove-admin') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id')
                    },
                    success: function(response) {
                        // alert("Project has been successfully accepted.");
                        location.reload();
                    }
                });
            });

            $(document).on('click', '.action-remove-inv', function() {
                $.ajax({
                    // url: "{{ url('/project-inv-remove-admin') }}".replace("http://", "https://"),
                    url: "{{ url('/project-inv-remove-admin') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id')
                    },
                    success: function(response) {
                        // alert("Project has been successfully accepted.");
                        location.reload();
                    }
                });
            });


            $(document).on('click', '.action-accept', function() {
                $.ajax({
                    url: "{{ url('/project-accept-admin') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id')
                    },
                    success: function(response) {
                        console.log(response);
                        // alert("Project has been successfully accepted.");
                        location.reload();
                    },
                    error: function() {
                        alert("error!!!!");
                    }

                });
            });


            $(document).on("click", ".action-free", function() {
                var dataValue = $(this).attr('dataValue');
                $(this).attr("disabled", "disabled");
                $.ajax({
                    url: "{{ url('/project-free') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id'),
                        dataValue: dataValue
                    },
                    success: function(response) {
                        if (response.msg == "Success") {
                            alert("The project free status has been changed successfully.");
                        }
                    },
                    error: function() {
                        alert("error!!!!");
                    }

                });
            });


            $(document).on("click", ".action-special", function() {
                var dataValue = $(this).attr('dataValue');
                $(this).attr("disabled", "disabled");
                $.ajax({
                    url: "{{ url('/project-special') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id'),
                        dataValue: dataValue
                    },
                    success: function(response) {
                        if (response.msg == "Success") {
                            alert("The project special status has been changed successfully.");
                        }
                    },
                    error: function() {
                        alert("error!!!!");
                    }

                });
            });

            $(document).on("click", ".action-service", function() {
                var dataValue = $(this).attr('dataValue');
                $(this).attr("disabled", "disabled");
                $.ajax({
                    url: "{{ url('/project-service') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id'),
                        dataValue: dataValue
                    },
                    success: function(response) {
                        if (response.msg == "Success") {
                            alert("The project service status has been changed successfully.");
                        }
                    },
                    error: function() {
                        alert("error!!!!");
                    }

                });
            });
            $(document).on('click', '.action-jury', function() {
                $.ajax({
                    url: "{{ url('/project-jury-admin') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id')
                    },
                    success: function(response) {
                        // alert("Project has been successfully accepted.");
                        location.reload();
                    }
                });
            });

            $(document).on('click', '.action-inv', function() {
                $.ajax({
                    url: "{{ url('/project-inv-admin') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: $(this).attr('id')
                    },
                    success: function(response) {
                        // alert("Project has been successfully accepted.");
                        location.reload();
                    }
                });
            });


            var rejectionId = 0;
            $(document).on('click', '.action-reject', function() {
                rejectionId = $(this).attr('id');
                $('#myModal').modal('toggle');
                $('.modal-backdrop').css('position', 'relative');

            });
            $('#model-cancel').click(function() {
                $('#myModal').modal('toggle');
                $('.email-body').val('');
            });

            $('#model-send-email').click(function() {
                $('#myModal').modal('toggle');
                var emailBody = $(".email-body").val();
                //return false;
                $.ajax({
                    url: "{{ url('project-reject-admin') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        "id": rejectionId,
                        "emailBody": emailBody
                    },
                    success: function(response) {
                        console.log(response);
                        // alert("Project has been rejected.");
                        location.reload();
                    }
                });
            });


            var deletingId = 0;
            $(document).on('click', '.action-delete', function() {
                deletingId = $(this).attr('id');
                $('#Modal-delete').modal('toggle');
                $('.modal-backdrop').css('position', 'relative');

            });
            $('#model-delete-cancel').click(function() {
                $('#Modal-delete').modal('toggle');
            });
            $(document).on('click', '#model-final-delete', function() {
                $.ajax({
                    url: "{{ url('/project-delete-admin') }}".replace("http://", "https://"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        id: deletingId
                    },
                    success: function(response) {
                        // alert("Project has been successfully accepted.");
                        location.reload();
                    }
                });
            });


        });
    </script>

    <script>
        function openModal(projectName) {
            document.getElementById('myModal-' + projectName).style.display = "block";
        }

        function del(imageName, md5) {
            var token = $('input[name="ajax_token"]').val();
            $.ajax({
                url: "{{ url('/show-delete') }}".replace("http://", "https://"),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data: {
                    fileName: imageName
                },
                success: function(response) {
                    $('#thumb-' + md5).remove();
                    // $('#wide-'+md5).remove();
                    $('#slide-' + md5).remove();
                }
            });
        }

        function closeModal(projectName) {
            document.getElementById('myModal-' + projectName).style.display = "none";
        }
        var slideIndex = {};

        // showSlides(slideIndex["<?php echo $project->projektname; ?>"] , "<?php echo $project->projektname; ?>");

        function plusSlides(n, projectName) {
            showSlides(slideIndex[projectName] += n, projectName);
        }

        function currentSlide(n, projectName) {
            showSlides(slideIndex[projectName] = n, projectName);
        }

        function showSlides(n, projectName) {
            var i;
            var slides = document.getElementsByClassName("mySlides-" + projectName);
            var dots = document.getElementsByClassName("demo-" + projectName);

            if (n > slides.length) {
                slideIndex[projectName] = 1
            }
            if (n < 1) {
                slideIndex[projectName] = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex[projectName] - 1].style.display = "block";
            dots[slideIndex[projectName] - 1].className += " active";

        }
    </script>

@endsection

@section('additional-styles')
    <style>
        body {
            font-family: Verdana, sans-serif;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        .wide_wrapper {
            width: 1200px;
            /*max-height : 400px;
        /* min-height : 400px;*/
            overflow: hidden;
        }

        .image-wrapper {
            border: solid 5px white;
            overflow: hidden;
        }

        .glyphicon {
            padding: 10px;
            color: #474747;
            font-size: 16px;
            cursor: pointer;
        }

        .glyphicon:hover {
            color: #c9c9c9;
        }

        .row>.column {
            padding: 0 8px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .column {
            float: left;
            width: 20%;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: black;
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            width: 90%;
            max-width: 1200px;
        }

        /* The Close Button */
        .close {
            color: white;
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 35px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #999;
            text-decoration: none;
            cursor: pointer;
        }

        .mySlides {
            display: none;
            data-responsive: true;
        }

        .cursor {
            cursor: pointer
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 30%;
            width: auto;
            padding: 16px;
            margin-top: -50px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        img {
            margin-bottom: -4px;
        }

        .caption-container {
            text-align: center;
            background-color: black;
            padding: 2px 16px;
            color: white;
        }

        .demo {
            opacity: 0.6;
        }

        .active,
        .demo:hover {
            opacity: 1;
        }

        img.hover-shadow {
            transition: 0.3s
        }

        .hover-shadow:hover {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
        }


        @media only screen and (min-width: 900px) {

            .big-slider-image-container {
                max-width: 500px;
                margin: 0 auto;
            }

            .big-slider-image {
                width: auto;
                max-height: 600px;
            }
        }

        @media only screen and (max-width: 899px) {

            .big-slider-image-container {
                width: 100%;
                height: 100%;
                margin: 0 auto
            }

            /*  .big-slider-image{
            max-width: 100%;
            height: 100%;
          }*/
        }
    </style>
@endsection

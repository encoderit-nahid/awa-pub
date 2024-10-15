@extends('layouts.app')
@section('additional-styles')
    <link rel="stylesheet" href="{{ asset('css/single-project.css') }}">
    <style>
        div#social-links {
            margin: 0 auto;
            max-width: 500px;
        }

        div#social-links ul li {
            display: inline-block;
        }

        div#social-links ul li a {
            padding: 20px;
            border: 1px solid #ccc;
            margin: 1px;
            font-size: 30px;
            color: #222;
            background-color: #ccc;
        }
    </style>
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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Projekte anzeigen...</div>
                    <div class="card-body">
                        @foreach ($projects as $project)
                            <br>
                            <hr><br>
                            <p style=""><b>Kategorie: {{ $project->cat_name }}
                                    <p style=""><b>Projektname: {{ $project->projektname }} ID:
                                            {{ $project->id }}</b></p>
                                    <p style=""><b>Datum: {{ $project->datum }} Ort: {{ $project->ort }}</b></p>
                                    <p style=""><b>Copyright: {{ $project->copyright }}</b></p>
                                    @if ($project->stat == 0)
                                        @if ($project->stat == 0 && $project->is_selected_for_first_evaluation)
                                            <p style=""><b>Projektstatus: Zur Bewertung freigegeben</b></p>
                                        @else
                                            <p style=""><b>Projektstatus: abgespeichert</b></p>
                                        @endif
                                    @elseif ($project->stat == 2)
                                        @if ($project->jury == 0 && $project->inv == 0)
                                            <p style=""><b>Projektstatus: Für Rechnung freigegeben</b></p>
                                        @elseif ($project->jury == 0 && $project->inv == 1)
                                            <p style=""><b>Projektstatus: Zur Rechnungslegung freigeben</b></p>
                                        @elseif ($project->jury == 1)
                                            <p style=""><b>Projektstatus: Zur Bewertung freigegeben</b></p>
                                        @endif
                                    @elseif ($project->stat == 3)
                                        <p style=""><b>Projektstatus: zurückgewiesen</b></p>
                                    @endif

                                    {{-- @dd($user->rolle); --}}
                                    @if ($user->changes == 0)
                                        <!-- if ($user->rolle == 0 || $user->rolle == 5) -->
                                        @if ($user->rating_visible == 1)
                                            <hr>
                                            @if ($project->rating_visible)
                                                <p style="">
                                                    <b>Bewertung:
                                                        @php
                                                            $max = max($cat_ids[$project->cat_id]);
                                                            $point = 100 - (($max - $project->count->sum('counts')) / $max) * 100;
                                                            if ($point >= 60) {
                                                                $star = 4;
                                                            } elseif ($point >= 20) {
                                                                $star = 3;
                                                            } else {
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
                                                        <!-- @isset($project->count)
                                                            @if ($project->count->sum('counts') >= 700)
                                                                <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>

                                                            @elseif($project->count->sum('counts') >= 499 && $project->count->sum('counts') <= 690)
                                                                <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="far fa-star"></i>

                                                            @elseif($project->count->sum('counts') >= 399 && $project->count->sum('counts') <= 498)
                                                                <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>

                                                            @elseif($project->count->sum('counts') >= 100 && $project->count->sum('counts') <= 398)
                                                                <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>

                                                            @elseif($project->count->sum('counts') >= 199 && $project->count->sum('counts') <= 298)
                                                                <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>

                                                            @else
                                                                <i class="fas fa-star en-rating-yellow"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>
                                                                                                                                                              <i class="far fa-star"></i>

                                                            @endif
                                                        @else
                                                            <i class="far fa-star"></i>
                                                                                                                                                      <i class="far fa-star"></i>
                                                                                                                                                      <i class="far fa-star"></i>
                                                                                                                                                      <i class="far fa-star"></i>
                                                                                                                                                      <i class="far fa-star"></i>
                                                                                                                                                      <i class="far fa-star"></i>

                                                        @endisset -->
                                                    </b>
                                                </p>
                                            @endif
                                        @endif
                                        <!--  <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                                data-target="#project-showing-{{ $project->id }}">Teilen?</button> -->
                                        <br>
                                        <hr>

                                        <div id="project-showing-{{ $project->id }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Share Social</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>
                                                            Schau mal, mein Projekt {{ $project->projektname }} hat eine
                                                            @isset($project->count)
                                                                @if ($project->count->sum('counts') >= 700)
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                @elseif($project->count->sum('counts') >= 499 && $project->count->sum('counts') <= 690)
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="far fa-star"></i>
                                                                @elseif($project->count->sum('counts') >= 399 && $project->count->sum('counts') <= 498)
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                @elseif($project->count->sum('counts') >= 299 && $project->count->sum('counts') <= 398)
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                @elseif($project->count->sum('counts') >= 199 && $project->count->sum('counts') <= 298)
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                @else
                                                                    <i class="fas fa-star en-rating-yellow"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                @endif
                                                            @else
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                            @endisset
                                                            Bewertung beim Austrian Wedding Award bekommen!
                                                        </p>
                                                        @php
                                                            $i = 0;
                                                            if (isset($project->count)) {
                                                                if ($project->count->sum('counts') >= 700) {
                                                                    $i = 6;
                                                                } elseif ($project->count->sum('counts') >= 499 && $project->count->sum('counts') <= 690) {
                                                                    $i = 5;
                                                                } elseif ($project->count->sum('counts') >= 399 && $project->count->sum('counts') <= 498) {
                                                                    $i = 4;
                                                                } elseif ($project->count->sum('counts') >= 299 && $project->count->sum('counts') <= 398) {
                                                                    $i = 3;
                                                                } elseif ($project->count->sum('counts') >= 199 && $project->count->sum('counts') <= 298) {
                                                                    $i = 2;
                                                                } else {
                                                                    $i = 1;
                                                                }
                                                            } else {
                                                                $i = 0;
                                                            }
                                                            $text = "Schau mal, mein Projekt $project->projektname hat eine  ";
                                                            for ($j = 1; $j <= $i; $j++) {
                                                                $text .= '&#9733;';
                                                            }
                                                            $text .= ' Bewertung beim Austrian Wedding Award bekommen!';
                                                            $share = new \Jorenvh\Share\Share();
                                                            $shareComponent = $share
                                                                ->page(url('/'), $text)
                                                                ->facebook()
                                                                ->twitter()
                                                                ->linkedin()
                                                                ->telegram()
                                                                ->reddit();

                                                        @endphp
                                                        <br>
                                                        {!! $shareComponent !!}

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Schließen
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif


                                    <br>


                                    @if ($project->youtube)
                                        <!-- <h1>Loading5....</h1> -->
                                        <h3><a href="{{$project->youtube}}" class="btn btn-primary">View Video</a></h3>
                                        <!-- dropbox -->
                                        <!-- <iframe src="{{ $project->youtube }}" height="280px" width="640px" allowfullscreen></iframe> -->
                                        <!-- <div style="padding:56.25% 0 0 0;position:relative;"><iframe
                                                                    src="{{ $project->youtube . '?h=60b811dbb0&amp;badge=0&amp;autopause=0&amp' }};autopause=0&amp;player_id=0&amp;app_id=58479"
                                                                    frameborder="0" allow="autoplay; fullscreen; picture-in-picture"
                                                                    allowfullscreen
                                                                    style="position:absolute;top:0;left:0;width:50%;height:50%;"
                                                                    title="Wedding Award Germany"></iframe></div>
                                                            <script src="https://player.vimeo.com/api/player.js"></script> -->
                                    @endif
                                    @if ($project->beschreibung)
                                        <div class="form-group">
                                            <label for="comment">Projektinfos:</label>
                                            <textarea class="form-control" rows="5" id="comment" readonly>{{ $project->beschreibung }}
                </textarea>
                                        </div>
                                    @endif
                                    @if ($project->testimonial)
                                        <br>
                                        <div class="form-group">
                                            <label for="comment">Referenzen: </label>
                                            <textarea class="form-control" rows="5" id="comment" readonly>{{ $project->testimonial }}
                </textarea>
                                        </div>
                                    @endif
                                    @if ($project->extra != '')
                                        <br>
                                        <div class="form-group">
                                            <label for="comment">Extras: </label>
                                            <textarea class="form-control" rows="5" id="comment" readonly>{{ $project->extra }} {{ $project->ort }} - {{ $project->datum }}
                </textarea>
                                        </div>
                                    @endif
                                    <br>
                                    <div class="row">
                                            <?php $imageCount = 0; ?>
                                        @foreach ($project->images as $image)
                                                <?php $imageCount++; ?>
                                            <div class="column">
                                                <div class="img-wrapper" id="thumb-<?php echo md5($image->filename); ?>">
                                                    <img src="{{ $image->thumb_url }}" alt="{{ $image->filename }}"
                                                         style=''
                                                         onclick="openModal('{{ $project->projektname }}'); currentSlide(<?php echo $imageCount; ?> , '<?php echo $project->projektname; ?>')"
                                                         class="hover-shadow demo-<?php echo $project->projektname; ?> cursor">
                                                </div>
                                            </div>
                                        @endforeach


                                    </div>
                                    <br>
                                    @if ($project->stat != 2)
                                        <form method="POST" action="{{ route('project-change') }}">
                                            @csrf
                                            {{ Form::hidden('projectID', $project->id) }}
                                            {{ Form::hidden('catID', $project->cat_id) }}
                                            <!-- ab hier nach dem 15. November 2019 zumachen -->
                                            @if ($user->rolle == 0 || $user->rolle == 9 || $user->rolle == 5)
                                                @if ($project->stat == 1)
                                                    <button type="submit" class="btn btn-primary" value="delete"
                                                            name="submit">
                                                        {{ __('Löschen') }}
                                                    </button>
                                                    <button type="submit" class="btn btn-primary" value="change"
                                                            name="submit">
                                                        {{ __('Ändern') }}
                                                    </button>
                                                    <a href="{{ url('/project/add-image/' . $project->id . '/' . $project->cat_id) }}"
                                                       class="btn btn-primary" disabled>Bild hinzufügen</a>
                                                    <a href="{{ url('/project/edit-image/' . $project->id . '/' . $project->cat_id) }}"
                                                       class="btn btn-primary" disabled>Bild(er) ändern</a>
                                                @elseif ($project->stat == 0)
                                                    <button type="submit" class="btn btn-primary" value="delete"
                                                            name="submit">
                                                        {{ __('Löschen') }}
                                                    </button>
                                                    <button type="submit" class="btn btn-primary" value="change"
                                                            name="submit">
                                                        {{ __('Ändern') }}
                                                    </button>
                                                    <a href="{{ url('/project/add-image/' . $project->id . '/' . $project->cat_id) }}"
                                                       class="btn btn-primary">Bild hinzufügen</a>
                                                    <a href="{{ url('/project/edit-image/' . $project->id . '/' . $project->cat_id) }}"
                                                       class="btn btn-primary">Bild(er) ändern</a>
                                                @endif
                                            @endif
                                            <!-- bis hier kommentieren -->
                                        </form>
                                    @endif


                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div id="myModal-{{ $project->projektname }}"
                                                 class="modal modal modal-height-width en-p-20">
                                                <span class="close cursor en-fs-30"
                                                      onclick="closeModal('{{ $project->projektname }}')">&times;</span>
                                                <div class="modal-content p-relative en-p-40 w-auto-h-100">
                                                    <div
                                                            class="wide_wrapper text-center big-slider-image-container w-auto-h-100">
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
                                                                 data-responsive="true" id="wide-<?php echo md5($image->filename); ?>">

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

                                                    <div style="height : 30px;"></div>

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
                                                            @if ($user->rolle == 0)
                                                                <div class="column clearfix"
                                                                     id="slide-<?php echo md5($image->filename); ?>">
                                                                    <div class="clearfix text-center"
                                                                         style="background : grey">

                                                                    </div>
                                                                    <div class="image-wrapper">
                                                                        <img id="slideimg-<?php echo md5($image->filename); ?>"
                                                                             class="demo-<?php echo $project->projektname; ?> cursor"
                                                                             src="{{ url($thumb_url) }}"
                                                                             style="width:100%"
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Youtube Modal -->
    <div class="modal fade" id="myYoutube" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                    <iframe id="iframeYoutube" width="100%" height="300px" src="" frameborder="0"
                            allowfullscreen></iframe>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Youtube popup
            $(document).on("click", ".youtube-btn", function () {
                var link = $(this).attr('link');
                var newarr = link.split('/');
                if (newarr.length == 4) {
                    var vimeoId = newarr[3];
                    showYoutube(vimeoId);
                } else {
                    alert('Video Invalid');
                }
            });

            $("#myYoutube").on("hidden.bs.modal", function () {
                $("#iframeYoutube").attr("src", "#");
            });

            function showYoutube(id) {
                var src = "//player.vimeo.com/video/" + id;
                // src = src.replace('watch?v=', 'embed/');
                $("#iframeYoutube").attr("src", src);
                $("#myYoutube").modal("show");
                $('.modal-backdrop').css('position', 'relative');
            }
        });


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
                success: function (response) {
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
        <?php foreach ($projects as $project) { ?>
        //   slideIndex["<?php echo $project->projektname; ?>"] = 1;
        //   showSlides(slideIndex["<?php echo $project->projektname; ?>"], "<?php echo $project->projektname; ?>");
        <?php } ?>

        function plusSlides(n, projectName) {
            showSlides(slideIndex[projectName] += n, projectName);
        }

        function currentSlide(n, projectName) {
            showSlides(slideIndex[projectName] = n, projectName);
        }

        // function showSlides(n, projectName) {
        //   var i;
        //   var slides = document.getElementsByClassName("mySlides-" + projectName);
        //   var dots = document.getElementsByClassName("demo-" + projectName);

        //   if (n > slides.length) {
        //     slideIndex[projectName] = 1
        //   }
        //   if (n < 1) {
        //     slideIndex[projectName] = slides.length
        //   }
        //   for (i = 0; i < slides.length; i++) {
        //     slides[i].style.display = "none";
        //   }
        //   // for (i = 0; i < dots.length; i++) {
        //   //     dots[i].className = dots[i].className.replace(" active", "");
        //   // }
        //   slides[slideIndex[projectName] - 1].style.display = "block";
        //   // dots[slideIndex[projectName]-1].className += " active";

        // }

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

        .img-wrapper {
            position: relative;
            padding-bottom: 100%;
            overflow: hidden;
            width: 100%;
        }

        .img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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

        .row > .column {
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

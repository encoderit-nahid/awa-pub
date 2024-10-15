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

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div>
                            Projekte anzeigen... 
                            <label style="float: right !important;">((<b>{{ $countPublicVotableProject }} Projekte sind Zur öffentlichen Abstimmung freigegeben</b>))</label>
                        </div>
                        <div class="link-div" style="text-align: center;width: 100%;display: inline-table;"><a class="btn btn-warning" href="/make-public-votable" id="{{1}}">zur öffentlichen Abstimmung veröffentlichen</a></div>
                    </div>
                    <div class="card-body">

                        {{-- <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right">
                                <h3>Category:</h3>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <select name="" id="category" class="form-control">
                                    <option value="">All</option>
                                    @foreach ($all_cats as $key => $val)
                                        <option value="{{ $key }}"
                                            @if ($key == $cat_id) selected @endif>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr> --}}

                        <section class="projects endless-pagination" data-next-page="{{ $projects->nextPageUrl() }}">
                            @foreach ($projects as $project)
                                <p style=""><b>Kategorie: {{ $project->cat_name }}
                                        <p style=""><b>Projektname: {{ $project->projektname }} ID:
                                                {{ $project->id }}</b></p>
                                        @if ($project->stat === 0)
                                            @if ($project->stat == 0 && $project->is_selected_for_first_evaluation)
                                                <p style=""><b>Projektstatus: Zur Bewertung freigegeben</b></p>
                                            @else
                                                <p style=""><b>Projektstatus: abgespeichert</b></p>
                                            @endif
                                        @elseif ($project->stat === 2)
                                            @if ($project->jury == 0 && $project->inv == 0)
                                                <p style=""><b>Projektstatus: Für Rechnung freigegeben</b></p>
                                            @elseif ($project->jury == 0 && $project->inv == 1)
                                                <p style=""><b>Projektstatus: Zur Rechnungslegung freigeben</b></p>
                                            @elseif ($project->jury == 1)
                                                <p style=""><b>Projektstatus: Zur Bewertung freigegeben</b></p>
                                            @endif
                                        @elseif ($project->stat === 3)
                                            <p style=""><b>Projektstatus: zurückgewiesen</b></p>
                                        @endif
                                        <br>
                                        <br>
                                        @if ($project->youtube != '')
                                            <!--  <p style=""> <button link="{{ $project->youtube }}" class="btn btn-primary youtube-btn">Video ansehen</button> </p> -->
                                            <!-- <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="{{ $project->youtube . '?h=60b811dbb0&amp;badge=0&amp;autopause=0&amp' }};autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:50%;height:50%;" title="Wedding Award Germany"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script> -->
                                                <h3><a href="{{$project->youtube}}" class="btn btn-primary">View Video</a></h3>
                            <!-- dropbox -->
                            <!-- <iframe src="{{ $project->youtube }}" height="280px" width="640px" allowfullscreen></iframe> -->
                                        @endif
                                        @if ($project->beschreibung != '')
                                            <div class="form-group">
                                                <label for="comment">Projektinfos:</label>
                                                <textarea class="form-control" rows="5" id="comment" readonly>{{ $project->beschreibung }}
                              </textarea>
                                            </div>
                                        @endif
                                        @if ($project->testimonial != '')
                                            <br>
                                            <div class="form-group">
                                                <label for="comment">Referenzen: </label>
                                                <textarea class="form-control" rows="5" id="comment" readonly>{{ $project->testimonial }}
                              </textarea>
                                            </div>
                                        @endif
                                        <?php /* 
						  @if($project->extra !="")

						  <br>
						  <div class="form-group">
                              <label for="comment">Extras: </label>
                              <textarea class="form-control" rows="5" id="comment" readonly>{{$project->extra}} {{$project->ort}} - {{$project->datum}}
                              </textarea>
                          </div>
						  @endif
              */
                                        ?>
                                        <br>
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
                                                        class="hover-shadow demo-<?php echo $project->projektname; ?> cursor">
                                                </div>
                                            @endforeach

                                        </div>
                                        <br>

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
                                                                @if ($user->rolle === 0)
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
                                        <button class="btn btn-primary action-accept" id="{{$project->id}}">Projekt zur öffentlichen Abstimmung entfernen</button>
                                        <hr/>
                                        <div style="height: 30px;"></div>
                            @endforeach
                        </section>
                        <div class="ajax-load text-center" style="display:none">
                            <p><img src="{{ asset('images/loading.gif') }}">Loading More post</p>
                        </div>


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

    <input type="hidden" id="do_work" value="{{ $do_work }}">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>


    <script type="text/javascript">
        var host = window.location.origin;
        var token = $('input[name="ajax_token"]').val();
        var page = $('.endless-pagination').data('next-page');

        function playYoutube() {

        }

        $(document).on('click', '.action-accept', function(){
            $.ajax({
                url: "{{ url('/update-public-votable') }}".replace("http://", "http://"),
                type: 'POST',
                    headers: {
                    'X-CSRF-TOKEN': token
                    },
                data: {
                    id : $(this).attr('id'),
                    status: 0,
                },
                success: function(response){
                    location.reload();
                },error:function(response){
                    // console.log('4-',response)
                    alert("error!!!!");
                }

            });
        });

        $(document).ready(function() {

            setTimeout(function(){
                $("div.alert").remove();
            }, 5000 ); // 5 secs

            $(window).scroll(fetchtickets);

            function fetchtickets() {

                var doWork = $('#do_work').val();

                if ((page !== null) && (doWork == '1')) {

                    clearTimeout($.data(this, "scrollCheck"));

                    $.data(this, "scrollCheck", setTimeout(function() {
                        var scroll_position_for_tickets_load = $(window).height() + $(window)
                        .scrollTop() + 300;
                        $('.ajax-load').show();

                        if (scroll_position_for_tickets_load >= $(document).height()) {
                            // page = page.replace("http://", "https://");
                            var pageLink = page.replace(host, '');
                            $.get(pageLink, function(data) {
                                $('.ajax-load').hide();
                                $('.projects').append(data.projects);
                                page = data.next_page;
                                $('.endless-pagination').data('next-page', data.next_page);
                            });
                        }
                    }, 1000))

                } else {
                    $('.ajax-load').show();
                    $('.ajax-load').html('<h2>No more post left</h2>');
                }
            }

            $(document).on("change", "#category", function() {
                var url = "{{ url('/project-show-rater') }}/" + $(this).val();
                window.location.replace(url);
            });
            // Youtube popup
            $(document).on("click", ".youtube-btn", function() {
                var link = $(this).attr('link');
                var newarr = link.split('/');
                if (newarr.length == 4) {
                    var vimeoId = newarr[3];
                    showYoutube(vimeoId);
                } else {
                    alert('Video Invalid');
                }
            });

            $("#myYoutube").on("hidden.bs.modal", function() {
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
        // <?php foreach($projects as $project){ ?>
        //   slideIndex["<?php echo $project->projektname; ?>"] = 1;
        //   showSlides(slideIndex["<?php echo $project->projektname; ?>"] , "<?php echo $project->projektname; ?>");
        // <?php }?>


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

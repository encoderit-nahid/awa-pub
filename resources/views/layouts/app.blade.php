<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
          integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('additional-styles')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>


<style>
    .text-huge {
        font-size: 1.8em;
    }

    .text-big {
        font-size: 1.4em;
    }

    .text-small {
        font-size: .85em;
    }

    .text-tiny {
        font-size: .7em;
    }

    .loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .load-overlay {
        width: 100%;
        height: 100%;
        position: fixed;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        top: 0;
        left: 0;
        display: none;
    }
</style>
<div class="load-overlay">
    <div class="loader"></div>
</div>
@yield('cron-message')
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a href="{{ url('/home') }}" class="navbar-left"><img src="{{ asset('logo_sml.jpg') }}"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li><a class="nav-link" href="{{ route('login') }}">{{ __('Einloggen') }}</a></li>
                        <li><a class="nav-link" href="{{ route('register') }}">{{ __('Registrieren') }}</a></li>
                    @else
                        @if (\Auth::user()->certificate == 1)
                            <li><a class="nav-link" href="{{ url('/download-certificate') }}">Download Certificate</a>
                            </li>
                        @endif
                        @if ($loggedUser->rolle == 9)
                            <li class="navbar-item dropdown">
                                <a class="nav-link dropdown-toggle" id="menu2 navbarDropdown"
                                   data-toggle="dropdown">Badge
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
                                    <li><a class="nav-link"
                                           href="{{ url('/show-badges/') }}">{{ __('Show Badge') }}</a></li>
                                    <li><a class="nav-link"
                                           href="{{ url('/create-badge/') }}">{{ __('Create Badge') }}</a></li>
                                    <li><a class="nav-link"
                                           href="{{ url('/create-award/') }}">{{ __('Create Award') }}</a></li>
                                    <li><a class="nav-link"
                                           href="{{ url('/show-awards/') }}">{{ __('Show Awards') }}</a></li>
                                </ul>
                            </li>
                            {{-- <li class="navbar-item dropdown">
                                <a class="nav-link dropdown-toggle" id="menu2 navbarDropdown"
                                    data-toggle="dropdown">Award
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
                                    <li><a class="nav-link"
                                            href="{{ url('/show-awards/') }}">{{ __('Show Awards') }}</a></li>
                                    <li><a class="nav-link"
                                            href="{{ url('/create-award/') }}">{{ __('Create Award') }}</a></li>
                                </ul>
                            </li> --}}
                            <li><a class="nav-link" href="{{ url('/show-score/') }}">{{ __('Export List') }}</a></li>
                        @endif

                        @if ($loggedUser->rolle == 0)
                            <!-- @if ($active_project_cnt != 0)
                                <li><a class="nav-link" href="{{ url('/downlaod/my-invoice/') }}">{{ __('Rechnung herunterladen') }}</a></li>





















                            @endif -->
                            <li><a class="nav-link" href="{{ route('my-badges') }}">{{ __('Badge') }}</a></li>
                            <li><a class="nav-link"
                                   href="{{ route('beschreibung') }}">{{ __('Wie funktionierts?') }}</a></li>
                            @if ($loggedUser->insert == 1)
                                <li><a class="nav-link"
                                       href="{{ route('project-insert') }}">{{ __('Projekt anlegen') }}</a></li>
                            @endif
                            <li><a class="nav-link"
                                   href="{{ route('project.rejected') }}">{{ __('Projekt abgelehnt') }}</a></li>

                            <li><a class="nav-link"
                                   href="{{ route('project-show') }}">{{ __('Projekt(e) anschauen') }}</a></li>
                        @elseif ($loggedUser->rolle == 5)
                            <li><a class="nav-link"
                                   href="{{ route('beschreibung') }}">{{ __('Wie funktionierts?') }}</a></li>
                            @if ($loggedUser->insert == 1)
                                <li><a class="nav-link"
                                       href="{{ route('project-insert') }}">{{ __('Projekt anlegen') }}</a></li>
                            @endif
                            <li><a class="nav-link"
                                   href="{{ route('project-show') }}">{{ __('Projekt(e) anschauen') }}</a></li>
                            <li><a class="nav-link"
                                   href="{{ route('project.rejected') }}">{{ __('Projekt abgelehnt') }}</a></li>
                            @if ($loggedUser->voting == 1)
                                <li><a class="nav-link"
                                       href="{{ route('votecoe') }}">{{ __('Projekt(e) bewerten') }}</a></li>
                            @endif
                        @elseif ($loggedUser->rolle == 1 || $loggedUser->rolle == 2)
                            @if ($loggedUser->voting == 1)
                                <li style="display: {{ $displayPermission ? 'none' : 'block' }}"><a class="nav-link"
                                                                                                    href="{{ route('project-show-1st-round') }}">{{ __('1. Bewertungsrunde') }}</a>
                                </li>
                                <li style="display: {{ $displayPermission ? 'block' : 'none' }}"><a class="nav-link"
                                                                                                    href="{{ route('project-freigegebene') }}">{{ __('2. Bewertungsrunde') }}</a>
                                </li>
                                <li><a class="nav-link"
                                       href="{{ route('project-show-rater') }}">{{ __('Projekt(e) bewerten') }}</a>
                                </li>
                            @endif
                        @else
                            {{-- ($loggedUser->rolle == 9) --}}
                            {{-- <li><a class="nav-link" href="{{ url('/change-user-status-new') }}">{{ __('New Status Change') }}</a></li> --}}
                            <li><a class="nav-link"
                                   href="{{ url('/change-user-status') }}">{{ __('User Status') }}</a></li>
                            <li class="navbar-item dropdown">
                                <a class="nav-link dropdown-toggle" id="menu2 navbarDropdown"
                                   data-toggle="dropdown">Invoice
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
                                    <li><a class="nav-link"
                                           href="{{ url('/invoice/') }}">{{ __('Invoice Name') }}</a>
                                    </li>
                                    <li><a class="nav-link"
                                           href="{{ url('/cat-invoice/') }}">{{ __('Invoice Cat') }}</a></li>
                                </ul>
                            </li>
                            {{-- <li><a class="nav-link" href="{{ url('/invoice/') }}">{{ __('Admin Invoice') }}</a></li>
                            <li><a class="nav-link"
                                    href="{{ url('/cat-invoice/') }}">{{ __('Admin Cat Invoice') }}</a></li> --}}
                            {{-- <li><a class="nav-link"
                                    href="{{ url('/selectuser/') }}">{{ __('Projekt hinzufügen') }}</a></li> --}}

                            {{-- <li class="navbar-item dropdown">
                                <a class="nav-link dropdown-toggle" id="menu2 navbarDropdown"
                                    data-toggle="dropdown">CoE
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
                                    <li><a class="nav-link"
                                            href="{{ route('CoE') }}">{{ __('Teilnehmer zu CoE hinzufügen') }}</a>
                                    </li>
                                    <li><a class="nav-link"
                                            href="{{ route('coeshow') }}">{{ __('CoE Teilnehmer anzeigen') }}</a>
                                    </li>
                                </ul>
                            </li> --}}

                            <li class="navbar-item dropdown">
                                <a class="nav-link dropdown-toggle" id="menu1 navbarDropdown"
                                   data-toggle="dropdown">Projekte
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                    <li><a class="nav-link"
                                           href="{{ url('/project-insert') }}">{{ __('Create Project') }}</a></li>
                                    <li><a class="nav-link"
                                           href="{{ url('/admin-project-show/1') }}">{{ __('Gelöschte Projekte') }}</a>
                                    </li>

                                    <li><a class="nav-link"
                                           href="{{ url('/admin-project-show/2') }}">{{ __('Freigegebene Projekte') }}</a>
                                    </li>

                                    <li><a class="nav-link"
                                           href="{{ url('/admin-project-show/3') }}">{{ __('Zurückgewiesene Projekte') }}</a>
                                    </li>

                                    <li><a class="nav-link"
                                           href="{{ url('/admin-project-show-all') }}">{{ __('Alle Projekte') }}</a>
                                    </li>
                                    <li><a class="nav-link"
                                           href="{{ url('/project-category-select') }}">{{ __('Projekte nach Kategorie') }}</a>
                                    </li>
                                </ul>
                            </li>



                            <li><a class="nav-link"
                                   href="{{ route('project-freigeben') }}">{{ __('Projekt(e) freigeben') }}</a></li>
                            {{-- <li><a class="nav-link"
                                    href="{{ route('project-rechnung') }}">{{ __('Projekt(e) Rechnung') }}</a></li> --}}
                            {{-- <li><a class="nav-link"
                                    href="{{ route('project-freigegebene') }}">{{ __('Jury Tasks') }}</a></li> --}}
                            <li class="navbar-item dropdown">
                                <a class="nav-link dropdown-toggle" id="menu2 navbarDropdown"
                                   data-toggle="dropdown">Jury Tasks
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
                                    <li style="display: {{ $displayPermission ? 'none' : 'block' }}"><a
                                                class="nav-link"
                                                href="{{ url('project-show-1st-round') }}">{{ __('1. Bewertungsrunde') }}</a>
                                    </li>
                                    <li style="display: {{ $displayPermission ? 'block' : 'none' }}"><a
                                                class="nav-link"
                                                href="{{ url('project-freigegebene') }}">{{ __('2. Bewertungsrunde') }}</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="nav-link" href="{{ url('/activity') }}">{{ __('Aktivitäten') }}</a></li>
                            <li><a class="nav-link" href="{{ url('/top-five') }}">{{ __('Top 10') }}</a></li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->anr }} {{ Auth::user()->vorname }} {{ Auth::user()->name }}
                                {{-- <span class="caret"></span> --}}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @if ($loggedUser->rolle == 9)
                                    <a class="dropdown-item"
                                       href="{{ route('round-visibility') }}">{{ __('Runde Sichtbarkeit') }}</a>
                                    <a class="dropdown-item"
                                       href="{{ route('project-votable') }}">{{ __('Sichtbarkeit der öffentlichen Abstimmung') }}</a>
                                    <a class="dropdown-item"
                                       href="{{ route('make-public-votable') }}">{{ __('zur öffentlichen Abstimmung veröffentlichen') }}</a>
                                @endif
                                <a class="dropdown-item"
                                   href="{{ route('user-change') }}">{{ __('Daten ändern') }}</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
@yield('additional-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.33.0/Dropbox-sdk.min.js"
        integrity="sha512-PTKs+sPreCz6TLyLj9CYx3LxxPZmY5k1k5Yb5Y5mUQzngf/XUxNdtyWwYjcPcOZJm4wSYiicZr0kotLmDIRFmQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php
if (!isset($access_token)) {
    $access_token = '';
}
?>

<script type="text/javascript">
    $(function () {
        console.log('888888888 - new code')
        var uploadVideoFlag = false;
        if ($("#youtube").length) {
            uploadVideoFlag = true;
        }

        // $('form').on("submit", function (e) {
        //     console.log('uploadVideoFlag', uploadVideoFlag)
        //     if (uploadVideoFlag && $("#youtube").prop('required')) {
        //         console.log('if')
        //         e.preventDefault();
        //         $(".load-overlay").css('display', 'flex');
        //         uploadFile();
        //     } else {
        //         console.log('else')
        //         console.log('test else')
        //         if (!$("#youtube").prop('required')) {
        //             // If youtube is not required, clear its value
        //             $("#youtube").val('');
        //         }
        //         $('form').off('submit').submit(); // Release form submission
        //     }
        // });

        $('form').on("submit", function (e) {
            console.log('uploadVideoFlag', uploadVideoFlag)
            if (uploadVideoFlag) {
                console.log('if')
                e.preventDefault();
                $(".load-overlay").css('display', 'flex');
                uploadFile();
            } else {
                console.log('else')
                console.log('test else')
                $("#youtube").val('');
                $("#youtube").removeAttr('required');

                $('form').off('submit').submit(); // Release
            }
        });

        async function uploadFile() {
            const UPLOAD_FILE_SIZE_LIMIT = 150 * 1024 * 1024;
            var ACCESS_TOKEN = '{{ $access_token }}';
            var dbx = new Dropbox.Dropbox({
                accessToken: ACCESS_TOKEN
            });
            var fileInput = document.getElementById('youtube');
            if (!fileInput.files.length && !$("#youtube").prop('required')) {
                // alert('Please choose a file to upload');
                $('form').off('submit').submit(); // Release
                // $(".load-overlay").css('display', 'none');
                return;
            }

            // get the max duration attribute
            const maxDuration = Number(fileInput.getAttribute('max-duration'));
            let inLimit = false
            if (maxDuration) {
                const video = document.createElement('video');
                video.preload = 'metadata';
                video.onloadedmetadata = await function () {
                    window.URL.revokeObjectURL(video.src);
                    if (maxDuration <= video.duration) {
                        inLimit = true;
                    }
                }
                video.src = URL.createObjectURL(fileInput.files[0]);
                if (!inLimit) {
                    $(".load-overlay").css('display', 'none');
                    alert('Please choose a file with duration less than ' + maxDuration + ' seconds');
                    return;
                }
            }

            var file = fileInput.files[0];
            var fileExt = file.name.split('.').pop();
            var newFilenameOnly = '{{ uniqid() }}';
            var dPath = '/' + newFilenameOnly + '.' + fileExt;

            if (file.size < UPLOAD_FILE_SIZE_LIMIT) { // File is smaller than 150 Mb - use filesUpload API
                dbx.filesUpload({
                    path: dPath,
                    contents: file
                })
                    .then(function (response) {
                        console.log('response11111111', response);
                        uploadVideoFlag = false;
                        $("#uploaded-youtube-file-name").val(dPath);
                        $("button[type=submit]").trigger('click'); // Release
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            } else { // File is bigger than 150 Mb - use filesUploadSession* API
                const maxBlob = 8 * 1000 * 1000; // 8Mb - Dropbox JavaScript API suggested max file / chunk size

                var workItems = [];

                var offset = 0;

                while (offset < file.size) {
                    var chunkSize = Math.min(maxBlob, file.size - offset);
                    workItems.push(file.slice(offset, offset + chunkSize));
                    offset += chunkSize;
                }

                const task = workItems.reduce((acc, blob, idx, items) => {
                    if (idx == 0) {
                        // Starting multipart upload of file
                        return acc.then(function () {
                            return dbx.filesUploadSessionStart({
                                close: false,
                                contents: blob
                            })
                                .then(response => response.result.session_id)
                        });
                    } else if (idx < items.length - 1) {
                        // Append part to the upload session
                        return acc.then(function (sessionId) {
                            console.log('sessionId', sessionId)
                            var cursor = {
                                session_id: sessionId,
                                offset: idx * maxBlob
                            };
                            return dbx.filesUploadSessionAppendV2({
                                cursor: cursor,
                                close: false,
                                contents: blob
                            }).then(() => sessionId);
                        });
                    } else {
                        // Last chunk of data, close session
                        return acc.then(function (sessionId) {
                            var cursor = {
                                session_id: sessionId,
                                offset: file.size - blob.size
                            };
                            var commit = {
                                path: dPath,
                                mode: 'add',
                                autorename: true,
                                mute: false
                            };
                            return dbx.filesUploadSessionFinish({
                                cursor: cursor,
                                commit: commit,
                                contents: blob
                            });
                        });
                    }
                }, Promise.resolve());

                task.then(function (result) {
                    console.log('resutl22222222', result);
                    console.log("dPath", dPath)
                    uploadVideoFlag = false;
                    $("#uploaded-youtube-file-name").val(dPath);
                    $("button[type=submit]").trigger('click'); // Release

                }).catch(function (error) {
                    console.error(error);
                });

            }
            return false;
        }
    });
</script>
</body>

</html>

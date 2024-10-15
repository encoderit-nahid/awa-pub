@extends('layouts.app')

@section('content')
    @if (session()->has('alert-success'))
        <div class="alert alert-success">
            {{ session()->get('alert-success') }}
        </div>
    @endif
    {{-- <div class="load-overlay">
        <div class="loader"></div>
    </div> --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">


                    <!-- <form class="form"> -->
                    <!-- <input type="text" id="access-token" placeholder="Access token" />
                        <input type="file" id="file-upload" />
                        <button type="button" id="jjj">Submit</button> -->
                    <!-- </form> -->
                    <div class="card-header">Schritt 3: Daten eintragen in die Kategorie: {{ $cats->name }}</div>
                    {{$cats->code}}
                    <div class="card-body">

                        @include('' . $cats->code)
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>






    <input type="hidden" id="hidden_vorname" value="{{ $user->vorname }}">
    <input type="hidden" id="hidden_name" value="{{ $user->name }}">
    <input type="hidden" id="hidden_email" value="{{ $user->email }}">
    <input type="hidden" id="hidden_companymail" value="{{ $user->companymail }}">
    <!-- dropbox -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.33.0/Dropbox-sdk.min.js"
        integrity="sha512-PTKs+sPreCz6TLyLj9CYx3LxxPZmY5k1k5Yb5Y5mUQzngf/XUxNdtyWwYjcPcOZJm4wSYiicZr0kotLmDIRFmQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <!-- <script src="https://unpkg.com/dropbox/dist/Dropbox-sdk.min.js"></script> -->

    <script type="text/javascript">
        $(function() {
            console.log('OLD Cole - 888')

            // var uploadVideoFlag = false;
            // if ($("#youtube").length) {
            //     uploadVideoFlag = true;
            // }

            // $('form').on("submit", function(e) {
            //     console.log('uploadVideoFlag', uploadVideoFlag)
            //     if (uploadVideoFlag) {
            //         console.log('if')
            //         e.preventDefault();
            //         $(".load-overlay").css('display', 'flex');
            //         uploadFile();
            //     } else {
            //         console.log('else')
            //         console.log('test else')
            //         $("#youtube").val('');
            //         $("#youtube").removeAttr('required');
                    
            //         $('form').off('submit').submit(); // Release 
            //     }
            // });

            // function uploadFile() {

            //     const UPLOAD_FILE_SIZE_LIMIT = 150 * 1024 * 1024;
            //     var ACCESS_TOKEN = '{{ $access_token }}';
            //     var dbx = new Dropbox.Dropbox({
            //         accessToken: ACCESS_TOKEN
            //     });
            //     var fileInput = document.getElementById('youtube');
            //     var file = fileInput.files[0];
            //     var fileExt = file.name.split('.').pop();
            //     var newFilenameOnly = '{{ uniqid() }}';
            //     var dPath = '/' + newFilenameOnly + '.' + fileExt;


            //     if (file.size < UPLOAD_FILE_SIZE_LIMIT) { // File is smaller than 150 Mb - use filesUpload API
            //         dbx.filesUpload({
            //                 path: dPath,
            //                 contents: file
            //             })
            //             .then(function(response) {
            //                 console.log('response11111111', response);
            //                 uploadVideoFlag = false;
            //                 $("#uploaded-youtube-file-name").val(dPath);
            //                 $("button[type=submit]").trigger('click'); // Release 
            //             })
            //             .catch(function(error) {
            //                 console.error(error);
            //             });
            //     } else { // File is bigger than 150 Mb - use filesUploadSession* API
            //         const maxBlob = 8 * 1000 * 1000; // 8Mb - Dropbox JavaScript API suggested max file / chunk size

            //         var workItems = [];

            //         var offset = 0;

            //         while (offset < file.size) {
            //             var chunkSize = Math.min(maxBlob, file.size - offset);
            //             workItems.push(file.slice(offset, offset + chunkSize));
            //             offset += chunkSize;
            //         }

            //         const task = workItems.reduce((acc, blob, idx, items) => {
            //             if (idx == 0) {
            //                 // Starting multipart upload of file
            //                 return acc.then(function() {
            //                     return dbx.filesUploadSessionStart({
            //                             close: false,
            //                             contents: blob
            //                         })
            //                         .then(response => response.result.session_id)
            //                 });
            //             } else if (idx < items.length - 1) {
            //                 // Append part to the upload session
            //                 return acc.then(function(sessionId) {
            //                     console.log('sessionId', sessionId)
            //                     var cursor = {
            //                         session_id: sessionId,
            //                         offset: idx * maxBlob
            //                     };
            //                     return dbx.filesUploadSessionAppendV2({
            //                         cursor: cursor,
            //                         close: false,
            //                         contents: blob
            //                     }).then(() => sessionId);
            //                 });
            //             } else {
            //                 // Last chunk of data, close session
            //                 return acc.then(function(sessionId) {
            //                     var cursor = {
            //                         session_id: sessionId,
            //                         offset: file.size - blob.size
            //                     };
            //                     var commit = {
            //                         path: dPath,
            //                         mode: 'add',
            //                         autorename: true,
            //                         mute: false
            //                     };
            //                     return dbx.filesUploadSessionFinish({
            //                         cursor: cursor,
            //                         commit: commit,
            //                         contents: blob
            //                     });
            //                 });
            //             }
            //         }, Promise.resolve());

            //         task.then(function(result) {
            //             console.log('resutl22222222', result);
            //             console.log("dPath", dPath)
            //             uploadVideoFlag = false;
            //             $("#uploaded-youtube-file-name").val(dPath);
            //             $("button[type=submit]").trigger('click'); // Release 

            //         }).catch(function(error) {
            //             console.error(error);
            //         });

            //     }
            //     return false;
            // }


            var hidden_vorname = $("#hidden_vorname").val();
            var hidden_name = $("#hidden_name").val();
            var hidden_email = $("#hidden_email").val();
            var hidden_companymail = $("#hidden_companymail").val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on('keyup', '#beschreibung,#testimonial', function() {
                console.log('working...');
                var $this = $(this)
                let content = $this.val();

                console.log('test', hidden_companymail.length)
                if (hidden_vorname.length != 0) {
                    if (content.toLowerCase().indexOf(hidden_vorname.toLowerCase()) != -1) {
                        var position = content.toLowerCase().indexOf(hidden_vorname.toLowerCase());
                        content = content.substring(0, position);
                        $this.val(content)
                        alert('You can not use ' + hidden_vorname)
                    }
                }
                if (hidden_name.length != 0) {
                    if (content.toLowerCase().indexOf(hidden_name.toLowerCase()) != -1) {
                        var position = content.toLowerCase().indexOf(hidden_name.toLowerCase());
                        content = content.substring(0, position);
                        $this.val(content)
                        alert('You can not use ' + hidden_name)
                    }
                }
                if (hidden_email.length != 0) {
                    if (content.toLowerCase().indexOf(hidden_email.toLowerCase()) != -1) {
                        var position = content.toLowerCase().indexOf(hidden_email.toLowerCase());
                        content = content.substring(0, position);
                        $this.val(content)
                        alert('You can not use ' + hidden_email)
                    }
                }
                if (hidden_companymail.length != 0) {
                    if (content.toLowerCase().indexOf(hidden_companymail.toLowerCase()) != -1) {
                        var position = content.toLowerCase().indexOf(hidden_companymail.toLowerCase());
                        content = content.substring(0, position);
                        $this.val(content)
                        alert('You can not use ' + hidden_companymail)
                    }
                }
            });
        });
    </script>
@endsection

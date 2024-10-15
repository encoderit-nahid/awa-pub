@extends('layouts.app')

@section('content')
@if (session()->has('alert-success'))
<div class="alert alert-success">
    {{ session()->get('alert-success') }}
</div>
@endif
<div class="load-overlay"><div class="loader"></div></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">


                <!-- <form class="form"> -->
                <!-- <input type="text" id="access-token" placeholder="Access token" />
                <input type="file" required id="file-upload" />
                <button type="button" id="jjj">Submit</button> -->
                <!-- </form> -->
                    <div class="card-header">Bitte lade hier die Fotos für das AWABooklet hoch</div>
                    <div class="card-body">
                      <div>
                          <p>Vor dem Upload beachtet bitte folgende Dinge: </p>
                        </div>
                        <div>1. Benennt eure Fotos nach dem jeweiligen Fotocredit </div>
                        <div>2. Die Fotos und das Logo sollten <strong>300 dpi</strong> haben</div>
                        <div>3. Die Größe des Fotos kann 10x15 oder größer sein </div>
                        <br><br>

                    <form method="POST" action="{{ url('award-upload-by-user') }}">
                        @csrf


                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Projekt auswählen') }}</label>

                            <div class="col-md-6">
                                <select name="projektname" id="projektname" class="form-control" required="required" >
                                    @foreach($projects as $project)
                                        <option value="{{$project->projektname}}">{{$project->projektname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Fotocredits*') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text"  class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required>

                                @if ($errors->has('title'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Infos an uns*') }}</label>

                            <div class="col-md-6">

                                <textarea id="description" rows="10" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}"></textarea>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="check1" class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>

                            <div class="col-md-6">
                                <input type="checkbox" checked="checked" name="check1" id="check1" value="{{old('check1')}}" required><label for="check1"><span></span><p>Ich verfüge über das Nutzungsrecht</p></label>
                                @if ($errors->has('check1'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('check1') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="check2" class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>

                            <div class="col-md-6">
                                <input type="checkbox" checked="checked" name="check2" id="check2" value="{{old('check2')}}" required><label for="check2"><span></span><p>Ich erlaube die Nutzung der Bilder im AWA Booklet</p></label>
                                @if ($errors->has('check2'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('check2') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="logo" class="col-md-4 col-form-label text-md-right">Logo</label>

                            <div class="col-md-6">
                                <input id="logo" type="file" class="form-control{{ $errors->has('logo') ? ' is-invalid' : '' }}" name="logo" value="{{ old('logo') }}" >
                                <input type="hidden" id="uploaded-logo-file-name" name="uploaded_logo_file_name">
                                @if ($errors->has('logo'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('logo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="imageone" class="col-md-4 col-form-label text-md-right">Bild 1</label>

                            <div class="col-md-6">
                                <input id="imageone" type="file"  class="form-control{{ $errors->has('imageone') ? ' is-invalid' : '' }}" name="imageone" value="{{ old('imageone') }}" >
                                <input type="hidden" id="uploaded-imageone-file-name" name="uploaded_imageone_file_name">
                                @if ($errors->has('imageone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('imageone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="imagetwo" class="col-md-4 col-form-label text-md-right">Bild 2</label>

                            <div class="col-md-6">
                                <input id="imagetwo" type="file" class="form-control{{ $errors->has('imagetwo') ? ' is-invalid' : '' }}" name="imagetwo" value="{{ old('imagetwo') }}" >
                                <input type="hidden" id="uploaded-imagetwo-file-name" name="uploaded_imagetwo_file_name">
                                @if ($errors->has('imagetwo'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('imagetwo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="imagethree" class="col-md-4 col-form-label text-md-right">Bild 3</label>

                            <div class="col-md-6">
                                <input id="imagethree" type="file" class="form-control{{ $errors->has('imagethree') ? ' is-invalid' : '' }}" name="imagethree" value="{{ old('imagethree') }}" >
                                <input type="hidden" id="uploaded-imagethree-file-name" name="uploaded_imagethree_file_name">
                                @if ($errors->has('imagethree'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('imagethree') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- ///////////////////////////////////////////// -->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary submit-btn-js">
                                    Speichern
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



    <style>
        .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }
        .load-overlay{
            width: 100%;
            height: 100%;
            position: fixed;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            top: 0;
            left: 0;
            display: none;
        }
    </style>

    <!-- dropbox -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.33.0/Dropbox-sdk.min.js" integrity="sha512-PTKs+sPreCz6TLyLj9CYx3LxxPZmY5k1k5Yb5Y5mUQzngf/XUxNdtyWwYjcPcOZJm4wSYiicZr0kotLmDIRFmQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="https://unpkg.com/dropbox/dist/Dropbox-sdk.min.js"></script> -->

    <script type="text/javascript">
        console.log('500')
        $(function() {
            function uuidv4() {
                return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
                    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                );
            }

            var uploadlogoFlag = false;
            var uploadimageoneFlag = false;
            var uploadimagetwoFlag = false;
            var uploadimagethreeFlag = false;

            var checkFiles = true;


            $('.submit-btn-js').on("click", function () {
                // 
                uploading_process();
            });
function uploading_process(){
    if(checkFiles){
        if($("#logo").val()){
            uploadlogoFlag = true;
        }

        if($("#imageone").val()){
            uploadimageoneFlag = true;
        }

        if($("#imagetwo").val()){
            uploadimagetwoFlag = true;
        }

        if($("#imagethree").val()){
            uploadimagethreeFlag = true;
        }

        checkFiles = false;
    }

    console.log(uploadlogoFlag,
    uploadimageoneFlag,
    uploadimagetwoFlag,
    uploadimagethreeFlag);

    if(uploadlogoFlag){
        
        $(".load-overlay").css('display', 'flex');
        uploadFile('logo');
    }
    else if(uploadimageoneFlag){
        
        $(".load-overlay").css('display', 'flex');
        uploadFile('imageone');
    }
    else if(uploadimagetwoFlag){
        
        $(".load-overlay").css('display', 'flex');
        uploadFile('imagetwo');
    }
    else if(uploadimagethreeFlag){
        
        $(".load-overlay").css('display', 'flex');
        uploadFile('imagethree');
    }else{
        console.log('else')
        console.log('test else')
        $('form').unbind( 'submit' ).submit(); // Release
    }
}            



function uploadFile(nameOfId) {
    console.log(nameOfId)

var projektname = $("#projektname").val();
// var projektname = '7777';

const UPLOAD_FILE_SIZE_LIMIT = 150 * 1024 * 1024;
var ACCESS_TOKEN = '{{$access_token}}';
var dbx = new Dropbox.Dropbox({ accessToken: ACCESS_TOKEN });
var fileInput = document.getElementById(nameOfId);
var file = fileInput.files[0];

var fileExt = file.name.split('.').pop();
var newFilenameOnly = '{{uniqid()}}';
var dPath = '/awa-booklet-2023/'+projektname+'/'+uuidv4()+'.'+fileExt;


if (file.size < UPLOAD_FILE_SIZE_LIMIT) { // File is smaller than 150 Mb - use filesUpload API
    dbx.filesUpload({
            path: dPath,
            contents: file
        })
        .then(function(response) {
            console.log('response11111111', response);
            if(nameOfId == "logo"){
                uploadlogoFlag = false;
            }
            if(nameOfId == "imageone"){
                uploadimageoneFlag = false;
            }
            if(nameOfId == "imagetwo"){
                uploadimagetwoFlag = false;
            }
            if(nameOfId == "imagethree"){
                uploadimagethreeFlag = false;
            }
            console.log(dPath)
            $("#uploaded-"+nameOfId+"-file-name").val(dPath);
            uploading_process();
        })
        .catch(function(error) {
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
            return acc.then(function() {
                return dbx.filesUploadSessionStart({
                        close: false,
                        contents: blob
                    })
                    .then(response => response.result.session_id)
            });
        } else if (idx < items.length - 1) {
            // Append part to the upload session
            return acc.then(function(sessionId) {
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
            return acc.then(function(sessionId) {
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

    task.then(function(result) {
        if(nameOfId == "logo"){
            uploadlogoFlag = false;
        }
        if(nameOfId == "imageone"){
            uploadimageoneFlag = false;
        }
        if(nameOfId == "imagetwo"){
            uploadimagetwoFlag = false;
        }
        if(nameOfId == "imagethree"){
            uploadimagethreeFlag = false;
        }
        console.log('resutl22222222', result);
        console.log("dPath", dPath)
        console.log(dPath)
        $("#uploaded-"+nameOfId+"-file-name").val(dPath);
        uploading_process();

    }).catch(function(error) {
        console.error(error);
    });

}
return false;
}


            // function uploadFileOLD(nameOfId) {

            //     alert(nameOfId)
                
            //     // var projektname = $("#projektname").val();
            //     var projektname = '666';

            //     const UPLOAD_FILE_SIZE_LIMIT = 150 * 1024 * 1024;
            //     var ACCESS_TOKEN = '{{$access_token}}';
            //     var dbx = new Dropbox.Dropbox({ accessToken: ACCESS_TOKEN });
            //     var fileInput = document.getElementById(nameOfId);
            //     var file = fileInput.files[0];

            //     var fileExt = file.name.split('.').pop();
            //     var newFilenameOnly = '{{uniqid()}}';
            //     var dPath = '/awa-booklet-2023/'+projektname+'/'+uuidv4()+'.'+fileExt;
            //     console.log('nameOfId', nameOfId)
            //     console.log('fileInput', fileInput)
            //     console.log('dPath', dPath)


            //     if (file.size < UPLOAD_FILE_SIZE_LIMIT) { // File is smaller than 150 Mb - use filesUpload API
            //         alert(1)
            //         dbx.filesUpload({path: dPath, contents: file})
            //         .then(function(response) {
                        
            //             if(nameOfId == "logo"){
            //                 uploadlogoFlag = false;
            //             }
            //             if(nameOfId == "imageone"){
            //                 uploadimageoneFlag = false;
            //             }
            //             if(nameOfId == "imagetwo"){
            //                 uploadimagetwoFlag = false;
            //             }
            //             if(nameOfId == "imagethree"){
            //                 uploadimagethreeFlag = false;
            //             }
            //             alert(3)
            //             alert(dPath)
            //             $("#uploaded-"+nameOfId+"-file-name").val(dPath);
            //             // $( "button[type=submit]" ).trigger('click'); // Release
            //         })
            //         .catch(function(error) {
            //         console.error(error);
            //         });
            //     } else { // File is bigger than 150 Mb - use filesUploadSession* API
            //     const maxBlob = 8 * 1000 * 1000; // 8Mb - Dropbox JavaScript API suggested max file / chunk size

            //     var workItems = [];

            //     var offset = 0;

            //     while (offset < file.size) {
            //         var chunkSize = Math.min(maxBlob, file.size - offset);
            //         workItems.push(file.slice(offset, offset + chunkSize));
            //         offset += chunkSize;
            //     }

            //     const task = workItems.reduce((acc, blob, idx, items) => {
            //         if (idx == 0) {
            //         // Starting multipart upload of file
            //         return acc.then(function() {
            //             return dbx.filesUploadSessionStart({ close: false, contents: blob})
            //                     .then(response => response.result.session_id)
            //         });
            //         } else if (idx < items.length-1) {
            //         // Append part to the upload session
            //         return acc.then(function(sessionId) {
            //             console.log('sessionId', sessionId)
            //         var cursor = { session_id: sessionId, offset: idx * maxBlob };
            //         return dbx.filesUploadSessionAppendV2({ cursor: cursor, close: false, contents: blob }).then(() => sessionId);
            //         });
            //         } else {
            //         // Last chunk of data, close session
            //         return acc.then(function(sessionId) {
            //             var cursor = { session_id: sessionId, offset: file.size - blob.size };
            //             var commit = { path: dPath, mode: 'add', autorename: true, mute: false };
            //             return dbx.filesUploadSessionFinish({ cursor: cursor, commit: commit, contents: blob });
            //         });
            //         }
            //     }, Promise.resolve());

            //     task.then(function(result) {
            //         if(nameOfId == "logo"){
            //             uploadlogoFlag = false;
            //         }
            //         if(nameOfId == "imageone"){
            //             uploadimageoneFlag = false;
            //         }
            //         if(nameOfId == "imagetwo"){
            //             uploadimagetwoFlag = false;
            //         }
            //         if(nameOfId == "imagethree"){
            //             uploadimagethreeFlag = false;
            //         }
            //         $("#uploaded-logo-file-name").val(dPath);
            //         $( "button[type=submit]" ).trigger('click'); // Release

            //     }).catch(function(error) {
            //         console.error(error);
            //     });

            //     }
            //     return false;
            // }


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection

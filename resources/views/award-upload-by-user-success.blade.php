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
                <input type="file" id="file-upload" />
                <button type="button" id="jjj">Submit</button> -->
                <!-- </form> -->
                    <!-- <div class="card-header"></div> -->
                    <div class="card-body">

                    <h1>Gratulation!!! Die Fotos wurden hochgeladen.</h1> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endsection

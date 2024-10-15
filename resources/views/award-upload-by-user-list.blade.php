@extends('layouts.app')

@section('content')
@if(session()->has('alert-success'))
    <div class="alert alert-success">
        {{ session()->get('alert-success') }}
    </div>
@endif
<div class="container-fluid">
  <div class="col">
    <p class="alert alert-success" style="display:none" id="msg"></p>
    </div>
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card">
              <div class="card-header">Uploaded Award List</div>

                  <div class="card-body">
                    <div class="row justify-content-center">
                      <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <!-- <form action="{{ url('/change-user-status') }}" method="get">
                            <input type="hidden" name="_token" value="lfBiJ409ebj36MMSjbov7k7BRU5joIdLK7cQZ8u7"> 
                            <div class="input-group mb-3">
                              <input type="text" placeholder="Name or email" aria-label="Search" aria-describedby="basic-addon2" name="search" value="" class="form-control"> 
                              <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-secondary">Search</button>
                              </div>
                            </div>
                        </form> -->
                      </div>
                    </div>


                    <div class="table-responsive">

                      <table class="table table-bordered text-center">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Project Name</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($uploadedAwards as $uploadedAward)
                              <tr>
                                <td>{{$uploadedAward->projektname}}</td>
                                <td>{{$projects[$uploadedAward->projektname]}}</td>
                                <td>{{$uploadedAward->created_at}}</td>
                              </tr>
                            @endforeach
                        </tbody>
                      </table>
                    </div>



                  </div>
                </div>
              </div>
            </div>
</div>

<input type="hidden" name="ajax_token" value="{{csrf_token()}}">

<script src="https://code.jquery.com/jquery-1.5.js"></script>

@endsection
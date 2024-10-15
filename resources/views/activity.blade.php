@extends('layouts.app')

@section('content')

@if(session()->has('alert-success'))
    <div class="alert alert-success">
        {{ session()->get('alert-success') }}
    </div>
@endif
<input type = "hidden" name = "ajax_token" value = "{{csrf_token()}}">
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="{{url('/activity')}}">
        @csrf 
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <h3><b>Search Type:</b></h3>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{-- <div class="radio"> --}}
                  <label>
                    <input type="radio" name="search_type" id="inputSearch_type" value="search_by_user_id">
                    User ID
                  </label>
                  <label>
                    <input type="radio" name="search_type" id="inputSearch_type" value="search_by_subject_id" checked="checked">
                    Subject ID
                  </label>
                {{-- </div>                 --}}
              </div>
            </div>

          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">

            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search By User ID" aria-label="Search" aria-describedby="basic-addon2" name="search">
              <div class="input-group-append">
                <button type="submit" class="btn btn-outline-secondary" type="button">Search</button>
              </div>
            </div>                          

          </div>
        </div>
      </form>
    </div>
  </div>
  <br>
  <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">Activity</div>
                <div class="card-body">
                	@foreach($activities as $activity)
                    @php
                      if ($activity->causer_id == Null) {
                        $caused_by = "**New User";
                      }else{
                        $caused_by = $users[$activity->causer_id];
                      }
                    @endphp
                  	<p><b>Subject Type:</b> {{$activity->subject_type}}</p>
                  	<p><b>Subject ID:</b> {{$activity->subject_id}}</p>
                  	<p><b>Description:</b> {{$activity->description}}</p>
                  	<p><b>Caused by:</b> {{$caused_by}}</p>
                  	<p><b>Date:</b> {{ Carbon\Carbon::parse($activity->created_at)->format('Y-m-d H:i:s')}} </p>
                  	<p><b>Properties:</b> <pre>{{$activity->properties}}</pre></p>
                  	<br><hr><br>

                	@endforeach

                  @if($is_paginate)
                  {{ $activities->links() }}
                  @endif
                </div>
            </div>
        </div>
    </div>
  </div>


@endsection

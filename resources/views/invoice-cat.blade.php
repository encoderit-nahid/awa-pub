@extends('layouts.app')

@section('content')
@if(session()->has('alert-success'))
    <div class="alert alert-success">
        {{ session()->get('alert-success') }}
    </div>
@endif


<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
      <form action="{{ url('/cat-invoice') }}" method="get">
        @csrf
        <div class="input-group mb-3">
          <div class="form-group">
            <label for="sel1">Select Category:</label>
            <select class="form-control" id="sel1" name="search_category" style="height: 30px;">
              <option>All</option>
              @foreach($all_cats as $cat)
              <option value="{{ $cat->id }}" @if($cat->id == request('search_category')) selected @endif>{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          {{-- <input type="text" placeholder="Name or email" aria-label="Search" aria-describedby="basic-addon2" name="search" class="form-control" value="{{ request('search') }}">  --}}
          <div class="input-group-append">
            <button type="submit" class="btn btn-outline-secondary btn-block">Search</button>
          </div>
        </div>
      </form>
    </div>

  </div>   
  <div class="row justify-content-center">   
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">Invoice Download Per Category</div>

                  <div class="card-body">

                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Download Invoice</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($cats as $cat)
                              <tr>
                                <td>{{$cat->id}}</td>
                                <td>{{$cat->name}}</td>
                                <td>{{$cat->code}}</td>
                                <td> <a href="{{ url('/downlaod/pdf-by-cat/'.$cat->id) }}" download="download"> <button class="btn btn-primary">Downlaod</button></a> </td>
                              </tr>
                            @endforeach
                        </tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div>
</div>

<script src="https://code.jquery.com/jquery-1.5.js"></script>


@endsection
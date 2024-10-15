@extends('layouts.app')

@section('content')
@if(session()->has('alert-success'))
    <div class="alert alert-success">
        {{ session()->get('alert-success') }}
    </div>
@endif
<div class="container-fluid">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card">
              <div class="card-header">Get Certificate</div>

                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered text-center">
                        <thead>
                          <tr>
                            <th>Rank</th>
                            <th>Firma</th>
                            <th>Category</th>
                            <th>Download Certification</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($position_per_cat as $key => $val)
                              <tr>
                                <td>{{ $val }}</td>
                                <td>{{ $firma }}</td>
                                <td>{{ $all_cats[$key] }}</td>
                                <td> 
                                  <a href="{{ url('/download-certificate-pdf/'.$val.'/'.$all_cats[$key]) }}"> <button class="btn btn-primary">Download</button></a> 
                                </td>
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

<script src="https://code.jquery.com/jquery-1.5.js"></script>


@endsection
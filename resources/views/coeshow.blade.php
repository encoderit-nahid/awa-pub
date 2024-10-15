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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"> <script> document.write('<a href="' + document.referrer + '">Go Back</a>');
</script> - CoE User anzeigen </div>
                  <div class="card-body">
                  	<div class="table-responsive">
	                  	<table class="table table-hover">
	                  		<thead>
	                  			<tr>
	                  				<th>Name</th>
	                  				<th>Vorname</th>
	                  				<th>Firma</th>
	                  				<th>Email</th>
	                  			</tr>
	                  		</thead>
	                  		<tbody>
			                  	@foreach($users as $project)
		                  			<tr>
		                  				<td>{{ $project->projektname }}</td>
		                  				<td>{{ $project->vorname }}</td>
		                  				<td>{{ $project->firma }}</td>
										<td>{{ $project->email }}</td>
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


@endsection
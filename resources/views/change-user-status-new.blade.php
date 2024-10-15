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
              <div class="card-header">Change User Status</div>

                  <div class="card-body">
                    <div class="row justify-content-center">
                      <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <form action="{{ url('/change-user-status-new') }}" method="get">
                            <input type="hidden" name="_token" value="lfBiJ409ebj36MMSjbov7k7BRU5joIdLK7cQZ8u7">
                            <div class="input-group mb-3">
                              <input type="text" placeholder="Name or email or firma" aria-label="Search" aria-describedby="basic-addon2" name="search" value="" class="form-control">
                              <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-secondary">Search</button>
                              </div>
                            </div>
                        </form>
                      </div>
                    </div>


                    <div class="table-responsive">

                      <table class="table table-bordered text-center">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Firma</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>User Type</th>
                            <th>Vote</th>
                            <th>Insert</th>
                          </tr>
                        </thead>
 {{-- state: user/voter/admin --}}
                        <tbody>
                            @foreach($users as $single_user)
                              <tr>
                                <td>{{$single_user->id}}</td>
                                <td>{{$single_user->firma}}</td>
                                <td>{{$single_user->vorname}} </td>
                                <td>{{$single_user->name}}</td>
                                <td>
                                    <select class="status-change-dd" id="rolle" user_id="{{ $single_user->id}}">
                                      <option value="0" @if($single_user->rolle == 0) selected @endif>User(0)</option>
                                      <option value="1" @if($single_user->rolle == 1) selected @endif>Voter(1)</option>
                                      <option value="5" @if($single_user->rolle == 5) selected @endif>Voter innovation(5)</option>
                                      <option value="9" @if($single_user->rolle == 9) selected @endif>Admin(9)</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="status-change-dd" id="voting" user_id="{{ $single_user->id}}">
                                      <option value="1" @if($single_user->voting == 1) selected @endif>Yes</option>
                                      <option value="0" @if($single_user->voting == 0) selected @endif>No</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="status-change-dd" id="insert" user_id="{{ $single_user->id}}">
                                      <option value="1" @if($single_user->insert == 1) selected @endif>Yes</option>
                                      <option value="0" @if($single_user->insert == 0) selected @endif>No</option>
                                    </select>
                                </td>
                              </tr>
                            @endforeach
                        </tbody>
                      </table>
                    </div>

                      <div class="row">
                        <div class="col-sm-12" >
                            {{ $users->links() }}
                        </div>
                      </div>

                  </div>
                </div>
              </div>
            </div>
</div>

<input type="hidden" name="ajax_token" value="{{csrf_token()}}">

<script src="https://code.jquery.com/jquery-1.5.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
      $('.status-change-dd').change(function(){
        var columnName  = $(this).attr("id");
        var user_id     = $(this).attr("user_id");
        var columnValue = $(this).val();
        var token = $('input[name="ajax_token"]').val();
        $.ajax({
            url: "{{ url('/status-change-dd') }}",
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': token
            },
            data: {
                'columnName' : columnName,
                'columnValue' : columnValue,
                'user_id' : user_id
            },
            success: function(response){
                console.log(response);
                $('#msg').show();
                $("#msg").html(response.msg);
                $("#msg").fadeOut(3000);
            }
        });
      });
    });
</script>
@endsection

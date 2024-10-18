@extends('layouts.app')

@section('content')
    @php
//        $users = \App\User::whereIn('rolle', [0, 9])->get();
        $users = \App\User::get();
    @endphp
    <div class="container-fluid">
        <form action="">
            <div class="row">
                @foreach($categories as $category)
                    @if($category->name)
                        <div class="col-12 col-md-6">
                            <div class="card rounded mb-4">
                                <div class="card-header">
                                    <h2>{{ $category->id }} -- {{$category->name}}</h2>
                                </div>
                                <div class="card-body">
                                    <h3>Assigned Users</h3>

                                    <select class="selectpicker" multiple data-live-search="true" style="width: 100%; font-size: 16px!important;">
                                        @foreach($users as $user)
                                            <option
                                                    value="{{$user->id}}"
                                                    @if($category->juryCategoryPermission->contains('user_id', $user->id)) selected @endif
                                            >
                                                {{$user->name}} : {{$user->email}}
                                            </option>
                                        @endforeach
                                    </select>

                                    <ul>
                                        @forelse($category->juryCategoryPermission as $jury_category_permission)
                                            @if($jury_category_permission->user)
                                                <li>
                                                    <h4>
                                                        {{$jury_category_permission->user->id}} -- {{$jury_category_permission->user->name}} : {{$jury_category_permission->user->email}}
                                                    </h4>
                                                    <ul>
                                                        @forelse($jury_category_permission->user->projects as $project)
                                                            <li>
                                                                <h5>
                                                                    {{$project->id}} -- {{$project->name}}
                                                                </h5>
                                                            </li>
                                                        @empty
                                                            <li class="text-danger">No projects assigned</li>
                                                        @endforelse
                                                    </ul>
                                                </li>
                                            @endif
                                        @empty
                                            <li class="text-danger">No users assigned</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </form>
    </div>
@endsection

@section('additional-js')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
@endsection
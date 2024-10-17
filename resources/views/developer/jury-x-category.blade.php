@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @foreach($categories as $category)
                @if($category->name)
                    <div class="col-12 col-md-6">
                        <div class="card rounded mb-4">
                            <div class="card-header">
                                <h2>{{$category->name}}</h2>
                            </div>
                            <div class="card-body">
                                <h3>Assigned Users</h3>
                                <ul>
                                    @forelse($category->juryCategoryPermission as $jury_category_permission)
                                        <li>
                                            <h4>
                                                {{$jury_category_permission->user->name}} - {{$jury_category_permission->user->email}}
                                            </h4>
                                        </li>
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
    </div>
@endsection
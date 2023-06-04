@extends('adminlte.default')

@section('title') Projects @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('projects.create')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-plus"></i> Project</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <hr>

        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover">
                <thead class="btn-dark">
                <tr>
                    <th>Name</th>
                    <th class="last">Action</th>
                </tr>
                </thead>
                <tbody class="blackboard-locations">
                @foreach($projects as $result)
                    <tr>
                        <td><a href="{{route('projects.show',$result)}}">{{$result->name}}</a></td>
                        <td class="last">
                            <a href="{{route('projects.edit',$result)}}" class="btn btn-success btn-sm">Edit</a>
                            {{ Form::open(['method' => 'get','route' => ['projects.destroy','id'=>$result],'style'=>'display:inline']) }}
                            <a href="#" class="delete deleteDoc btn btn-danger btn-sm">Delete</a>
                            {{Form::close() }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

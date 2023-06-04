@extends('adminlte.default')

@section('title') View Project @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('projects.index')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <hr />
        @foreach($projects as $result)
            <div class="form-group mt-3">
                {{Form::label('name', 'Name')}}
                {{$result->name}}
            </div>
    </div>
    @endforeach
@endsection
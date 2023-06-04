@extends('layouts.app')

@section('title') Clients @endsection

@section('header')
    <h1><i class="fa fa-address-card-o"></i> @yield('title')</h1>
    <a href="{{route('clients.create')}}" class="btn btn-outline-light float-right"><i class="fa fa-plus"></i> Client</a>
@endsection

@section('content')
    <form class="form-inline mt-3">
        Show &nbsp;
        {{Form::select('s',['all'=>'All','mine'=>'My','country'=>'Country wide','region'=>'Region wide','office'=>'Office wide'],old('selection'),['class'=>'form-control form-control-sm'])}}
        &nbsp; for &nbsp;
        {{Form::select('p',$processes ,old('p'),['class'=>'form-control form-control-sm'])}}
        &nbsp; for &nbsp;
        {{Form::select('c',['all'=>'All completions','no'=>'Not completed','yes'=>'Completed'],old('c'),['class'=>'form-control form-control-sm'])}}
        &nbsp; in &nbsp;
        {{Form::select('step',$steps ,old('step'),['class'=>'form-control form-control-sm'])}}
        &nbsp; from &nbsp;
        {{Form::date('f',old('f'),['class'=>'form-control form-control-sm'])}}
        &nbsp; to &nbsp;
        {{Form::date('t',old('t'),['class'=>'form-control form-control-sm'])}}
        &nbsp; matching &nbsp;
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fa fa-search"></i>
                </div>
            </div>
            {{Form::text('q',old('query'),['class'=>'form-control form-control-sm','placeholder'=>'Search...'])}}
        </div>
        <button type="submit" class="btn btn-sm btn-secondary ml-2 mr-2"><i class="fa fa-search"></i> Search</button>
        <a href="{{route('clients.index')}}" class="btn btn-sm btn-outline-info"><i class="fa fa-eraser"></i> Clear</a>
    </form>

    <hr>

    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
            <thead class="thead-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Process</th>
                <th>Created</th>
                <th>Completed</th>
                <th><abbr title="Days taken to complete a clients process.">Duration</abbr></th>
                <th>Step</th>
                <th>User</th>
            </tr>
            </thead>
            <tbody>
            @forelse($clients as $client)
                <tr>
                    <td><a href="{{route('clients.show',$client)}}">{{$client->company}}</a></td>
                    <td>{{$client->email}}</td>
                    <td>{{$client->contact}}</td>
                    <td>{{$client->process->name}}</td>
                    <td>{{$client->created_at}}</td>
                    <td>{{!is_null($client->completed_at) ? $client->completed_at->toDateString() : ''}}</td>
                    <td>{{$client->completed_days}}</td>
                    <td>{{$client->getCurrentStep()->name}}</td>
                    <td><a href="{{route('profile',$client->introducer)}}" title="{{$client->introducer->name()}}"><img src="{{route('avatar',['q'=>$client->introducer->avatar])}}" class="blackboard-avatar blackboard-avatar-inline" alt="Avatar"/></a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center"><small class="text-muted">No clients match those criteria.</small></td></td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>

    <small class="text-muted">Found <b>{{sizeof($clients)}}</b> clients matching those criteria.</small>
@endsection

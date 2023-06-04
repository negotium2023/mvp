@extends('adminlte.default')

@section('title') {{($client->company == '' || $client->company == 'N/A' || $client->company == 'n/a' ? $client->first_name.' '.$client->last_name : $client->company )}} @endsection

@section('header')
    <div class="container-fluid container-title form-inline">
        <div class="col-sm-4">
            <h3 class="form-inline">@yield('title')</h3>
        </div>
        @auth
        <div class="col-sm-7 process-dropdown" style="padding-top: 0px;">
        @if(isset($view_process_dropdown) && isset($client))
                <input type="hidden" value="{{$client->id}}" id="client_id" />
                <label class="float-left" style="margin-right: 10px;padding-top:5px;">View Application</label> <select class="chosen-select form-control form-control-sm float-left ml-3" id="viewprocess">
                @forelse($view_process_dropdown as $k=>$v)
                        <optgroup label="{{$k}}">
                        @foreach($v as $key=>$value)
                            <option value="{{$value['id']}}" {{(isset($process_id) && $process_id == $value['id'] ? 'selected' : '')}}>{{$value["name"]}}</option>
                        @endforeach
                        </optgroup>
                @empty
                    <option value="">There are no applications available for this client.</option>
                @endforelse
            </select>
        @else
            @forelse(auth()->user()->office()->processes->first()->steps as $step)
                <div class="col-lg blackboard-step-{{$step->id}}">
                    {{$step->name}}
                </div>
            @empty
                <p>There are no applications available for this client.</p>
            @endforelse
        @endif
        </div>
        <div class="col-sm-1 back-btn">
        <a href="{{(isset($path) && $path == 1 ? $path_route : route('clients.index'))}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-caret-left"></i> Back</a>
        </div>
        @endauth
    </div>
@endsection

@section('content')
<div class="container-fluid" style="padding-top: 20px;">
    @include('clients.process')

    <ul class="nav nav-tabs nav-fill mt-3">
        <li class="nav-item">
            <a class="nav-link {{active('clients.show','active')}}" href="{{route('clients.show',[$client,$process_id,$step["id"]])}}">Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(\Request::is('clients/*/progress/*') ? 'active' : '')}}" href="{{route('clients.progress',$client)}}/{{$process_id}}/{{$step["id"]}}">Progress</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{active('clients.documents','active')}}" href="{{route('clients.documents',[$client,$process_id,$step["id"]])}}">Documents</a>
        </li>
        <li class="nav-item">
            {{--<a class="nav-link {{active('clients.actions','active')}}" href="{{route('clients.actions',$client)}}">Actions</a>--}}
            {{--<a class="nav-link {{active('clients.actions','active')}}" href="javascript:void(0)" style="cursor: not-allowed;">Actions</a>--}}
        </li>
        <li class="nav-item">
            <a class="nav-link {{active('client.basket','active') || active('client.progress','active')}}" href="{{route('client.basket',[$client,$process_id,$step["id"]])}}">Client Basket</a>
        </li>
</ul>

<div class="row m-0 pt-3 pb-5 border border-top-0 activity-container">

@yield('tab-content')

</div>
</div>
@endsection
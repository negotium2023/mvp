@extends('adminlte.default')
@section('title') View Activities @endsection
@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
            <thead class="btn-dark">
            <tr>
                <th>Client</th>
                <th>Activity</th>
                <th>Date Assigned</th>
                <th>Assigned By</th>
            </tr>
            </thead>
            <tbody>
            @forelse($activities_log as $activity_log)
                <tr>
                    <td><a href="{{route('clients.show',$client[0])}}">{{$client[0]->company}}</a></td>
                    <td>{{$activity_log->activity_name}}</td>
                    <td>{{$activity_log->created_at}}</td>
                    <td>{{$user[0]->first_name}} {{$user[0]->last_name}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No activities match those criteria.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    </div>
@endsection
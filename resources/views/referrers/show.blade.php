@extends('adminlte.default')

@section('title') {{$referrer->name()}} @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
    <div class="btn-toolbar float-right">
        <div class="btn-group mr-2">
            <a href="{{route('referrers.index')}}" class="btn btn-dark btn-sm"><i class="fa fa-caret-left"></i> Back</a>
        </div>
        <div class="btn-group mr-2">
            <a href="{{route('referrers.edit',$referrer)}}" class="btn btn-dark btn-sm"><i class="fa fa-pencil"></i> Edit</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <hr />
    <ul class="mt-3">
        <dt>
            Email
        </dt>
        <dd>
            {{$referrer->email}}
        </dd>
        <dt>
            Contact
        </dt>
        <dd>
            {{$referrer->contact}}
        </dd>
        <dt>
            Created
        </dt>
        <dd>
            {{$referrer->created_at}}
        </dd>
    </ul>

    <h3>Clients</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
            <thead class="btn-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>UHY </th>
                <th>UHY Firm Name</th>
                <th>UHY Contact</th>
            </tr>
            </thead>
            <tbody>
            @forelse($referrer->clients as $client)
                <tr>
                    <td><a href="{{route('clients.show',$client)}}">{{$client->company}}</a></td>
                    <td>{{$client->email}}</td>
                    <td>{{$client->contact}}</td>
                    <td>{{$client->uhy_referrer}}</td>
                    <td>{{$client->uhy_firm_name}}</td>
                    <td>{{$client->uhy_contact}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No clients yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    </div>
@endsection

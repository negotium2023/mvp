@extends('adminlte.default')

@section('title') Referrers @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('referrers.create')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-plus"></i> Referrer</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
    <form class="form-inline mt-3">
        Show &nbsp;
        {{Form::select('s',['all'=>'All','mine'=>'My','company'=>'Branch'],old('selection'),['class'=>'form-control form-control-sm'])}}
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
    </form>

    <hr>

    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
            <thead class="btn-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>UHY Referral?</th>
                <th>UHY Firm Name</th>
                <th>UHY Contact</th>
                <th class="last">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($referrers as $referrer)
                <tr>
                    <td><a href="{{route('referrers.show',$referrer)}}">{{$referrer->name()}}</a></td>
                    <td>{{$referrer->email}}</td>
                    <td>{{$referrer->contact}}</td>
                    <td>{{$referrer->uhy_referral == 0?'No':'Yes'}}</td>
                    <td>{{$referrer->uhy_firm_name}}</td>
                    <td>{{$referrer->uhy_contact}}</td>
                    <td class="last">
                        <a href="{{route('referrers.edit',$referrer)}}" class="btn btn-success btn-sm">Edit</a>
                        <a href="{{route('referrers.edit',$referrer)}}" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No referrers match those criteria.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    </div>
@endsection

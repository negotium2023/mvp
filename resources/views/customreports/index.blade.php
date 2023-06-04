@extends('adminlte.default')

@section('title') Custom Reports @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <a href="{{route('custom_report.create')}}" class="btn btn-dark btn-sm float-right"><i class="fa fa-plus"></i> Report</a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <hr />
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-sm table-hover">
                <thead class="btn-dark">
                <tr>
                    <th>Name</th>
                    {{--<th>Activities</th>--}}
                    <th>Added By</th>
                    <th>Created</th>
                    <th class="last">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reports as $report)

                        <tr>
                            <td><a href="{{route('custom_report.show', $report->id)}}">{{$report->name}}</a></td>
                            {{--<td>

                            </td>--}}
                            <td>{{$report->user->first_name}} {{$report->user->last_name}}</td>
                            <td>{{$report->created_at}}</td>
                            <td class="last"><a href="{{route('custom_report.edit',['custom_report_id' => $report->id])}}" class="btn btn-success btn-sm">Edit </a>
                                {{ Form::open(['method' => 'DELETE','route' => ['custom_report.destroy','id'=>$report],'style'=>'display:inline']) }}
                                <a href="#" class="delete deleteDoc btn btn-danger btn-sm">Delete</a>
                                {{Form::close() }}</td>
                        </tr>

                @empty
                    <tr><td colspan="5">No Reports were found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
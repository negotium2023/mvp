@extends('flow.default')

@section('title') Locations @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
    <div class="btn-toolbar float-right">
        <div class="btn-group mr-2">
            <a href="{{route('divisions.create')}}" class="btn btn-dark btn-sm"><i class="fa fa-plus"></i> Division</a>
        </div>
        <div class="btn-group mr-2">
            <a href="{{route('regions.create')}}" class="btn btn-dark btn-sm"><i class="fa fa-plus"></i> Region</a>
        </div>
        <div class="btn-group mr-2">
            <a href="{{route('areas.create')}}" class="btn btn-dark btn-sm"><i class="fa fa-plus"></i> Area</a>
        </div>
        <div class="btn-group">
            <a href="{{route('offices.create')}}" class="btn btn-dark btn-sm"><i class="fa fa-plus"></i> Office</a>
        </div>
    </div>
    </div>
@endsection

@section('content')
    <div class="content-container">
        <div class="row col-md-12">
            @yield('header')
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
                    <a href="{{route('locations.index')}}" class="btn btn-sm btn-info"><i class="fa fa-eraser"></i> Clear</a>
                </form>

                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="btn-dark">
                        <tr>
                            <th>Division</th>
                            <th>Region</th>
                            <th>Area</th>
                            <th>Office</th>
                            <th class="last">Action</th>
                        </tr>
                        </thead>
                        <tbody class="blackboard-locations">
                        @forelse($divisions as $division)
                            <tr>
                                <td colspan="4">{{$division->name}}</td>
                                <td class="last">
                                    <a href="{{route('divisions.edit',$division)}}" class="btn btn-success btn-sm">Edit</a>
                                    <a href="" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            @forelse($division->regions->sortBy('name') as $region)
                                <tr>
                                    <td></td>
                                    <td colspan="3">{{$region->name}}</td>
                                    <td class="last">
                                        <a href="{{route('regions.edit',$region)}}" class="btn btn-success btn-sm">Edit</a>
                                        <a href="" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                @forelse($region->areas->sortBy('name') as $area)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2">{{$area->name}}</td>
                                        <td class="last">
                                            <a href="{{route('areas.edit',$area)}}" class="btn btn-success btn-sm">Edit</a>
                                            <a href="" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                    @forelse($area->offices->sortBy('name') as $office)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$office->name}}</td>
                                            <td class="last">
                                                <a href="{{route('offices.edit',$office)}}" class="btn btn-success btn-sm">Edit</a>
                                                <a href="" class="btn btn-danger btn-sm">Delete</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center">No Offices assigned to this area.</td>
                                        </tr>
                                    @endforelse
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No Areas assigned to this region.</td>
                                    </tr>
                                @endforelse
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">No Regions assigned to this division.</td>
                                </tr>

                            @endforelse
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">No locations match those criteria.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

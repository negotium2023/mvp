@extends('flow.default')

@section('title') Document Templates @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">
            <form autocomplete="off">
                <div class="form-row">
                    <div class="form-group">
                        <div class="input-group mt-2 pr-2">
                            {{Form::search('q',old('query'),['class'=>'form-control search','placeholder'=>'Search...'])}}
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{route('templates.create')}}" class="btn btn-primary btn-sm mt-3">Add Template</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            @yield('header')
            <div class="container-fluid index-container-content">
                <div class="table-responsive h-100">
                <table class="table table-bordered table-sm table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Uploader</th>
                        <th>Added</th>
                        <th class="last">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($templates as $template)
                        <tr>
                            <td>{{$template->name}}</td>
                            <td>{{$template->type()}}</td>
                            <td>{{$template->size()}}</td>
                            <td><a href="{{route('profile',$template->user)}}" title="{{$template->user->name()}}"><img src="{{route('avatar',['q'=>$template->user->avatar])}}" class="blackboard-avatar blackboard-avatar-inline" alt="{{$template->user->name()}}"/></a></td>
                            <td>{{$template->created_at->diffForHumans()}}</td>
                            <td class="last">
                                <a href="{{route('templates.edit',$template)}}" class="btn btn-success btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                {{ Form::open(['method' => 'DELETE','route' => ['templates.destroy', $template],'style'=>'display:inline']) }}
                                <a href="#" class="delete deleteDoc btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                {{Form::close() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">No templates match those criteria.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
